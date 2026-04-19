<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\WritingExercise;
use App\Models\WritingSubmission;
use App\Services\WritingEvaluationService;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    public function __construct(private readonly WritingEvaluationService $writingEvaluationService)
    {
    }

    public function show(WritingExercise $writingExercise)
    {
        $user = auth()->user();

        // Resolve course_id from lesson or courseLevel
        $courseId = $writingExercise->lesson?->course_id
                 ?? $writingExercise->courseLevel?->course_id;

        if (!$courseId || !$user->isEnrolledIn($courseId)) {
            abort(403);
        }

        $writingExercise->load(['lesson.course', 'courseLevel.course']);

        $attempts = $writingExercise->submissions()
            ->where('user_id', $user->id)
            ->latest('submitted_at')
            ->take(10)
            ->get();

        return view('student.writing.show', compact('writingExercise', 'attempts'));
    }

    public function submit(Request $request, WritingExercise $writingExercise)
    {
        $user = auth()->user();
        $courseId = $writingExercise->lesson?->course_id ?? $writingExercise->courseLevel?->course_id;

        if (!$courseId || !$user->isEnrolledIn($courseId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($writingExercise->evaluation_type === 'exact_match') {
            $request->validate(['answers' => ['required', 'array']]);
            $userAnswers = $request->input('answers');
            $questions = is_string($writingExercise->questions_json) ? json_decode($writingExercise->questions_json, true) : ($writingExercise->questions_json ?? []);
            
            $correctCount = 0;
            $totalQuestions = count($questions);
            $results = [];

            foreach ($questions as $index => $q) {
                $userAns = trim((string) ($userAnswers[$index] ?? ''));
                $correctAns = trim((string) $q['answer']);
                $isCorrect = strcasecmp($userAns, $correctAns) === 0;
                if ($isCorrect) $correctCount++;
                $results[] = [
                    'question' => $q['question'],
                    'expected' => $correctAns,
                    'user_answer' => $userAns,
                    'is_correct' => $isCorrect,
                ];
            }

            $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
            $passed = $score >= $writingExercise->passing_score;

            $submission = WritingSubmission::create([
                'writing_exercise_id' => $writingExercise->id,
                'user_id' => $user->id,
                'lesson_id' => $writingExercise->lesson_id,
                'answer_text' => json_encode($userAnswers),
                'word_count' => count(array_filter($userAnswers)),
                'status' => 'evaluated',
                'overall_score' => $score,
                'grammar_score' => $score,
                'vocabulary_score' => $score,
                'coherence_score' => $score,
                'task_score' => $score,
                'grammar_feedback_json' => [],
                'ai_feedback_json' => [
                    'summary' => 'Exact match evaluation completed.',
                    'strengths' => ["Correctly answered: $correctCount / $totalQuestions"],
                    'improvements' => [],
                    'exact_match_results' => $results,
                ],
                'rewrite_text' => '',
                'submitted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'submission_id' => $submission->id,
                'overall_score' => $score,
                'passed' => $passed,
                'exact_match_results' => $results,
            ]);
        }

        $request->validate([
            'answer_text' => ['required', 'string', 'min:10', 'max:12000'],
        ]);

        $answerText = trim((string) $request->input('answer_text'));
        $evaluation = $this->writingEvaluationService->evaluate($writingExercise, $answerText, app()->getLocale());

        $submission = WritingSubmission::create([
            'writing_exercise_id' => $writingExercise->id,
            'user_id' => $user->id,
            'lesson_id' => $writingExercise->lesson_id,
            'answer_text' => $answerText,
            'word_count' => $evaluation['word_count'],
            'status' => 'evaluated',
            'overall_score' => $evaluation['overall_score'],
            'grammar_score' => $evaluation['grammar_score'],
            'vocabulary_score' => $evaluation['vocabulary_score'],
            'coherence_score' => $evaluation['coherence_score'],
            'task_score' => $evaluation['task_score'],
            'grammar_feedback_json' => $evaluation['grammar_issues'],
            'ai_feedback_json' => [
                'summary' => $evaluation['summary'],
                'strengths' => $evaluation['strengths'],
                'improvements' => $evaluation['improvements'],
                'required_vocabulary_usage' => $evaluation['required_vocabulary_usage'] ?? 0,
                'used_vocabulary_count' => $evaluation['used_vocabulary_count'] ?? 0,
                'vocabulary_target_met' => $evaluation['vocabulary_target_met'] ?? true,
                'missing_vocabulary_words' => $evaluation['missing_vocabulary_words'] ?? [],
            ],
            'rewrite_text' => $evaluation['rewrite_suggestion'],
            'submitted_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'submission_id' => $submission->id,
            'word_count' => $evaluation['word_count'],
            'overall_score' => $evaluation['overall_score'],
            'grammar_score' => $evaluation['grammar_score'],
            'vocabulary_score' => $evaluation['vocabulary_score'],
            'coherence_score' => $evaluation['coherence_score'],
            'task_score' => $evaluation['task_score'],
            'summary' => $evaluation['summary'],
            'strengths' => $evaluation['strengths'],
            'improvements' => $evaluation['improvements'],
            'rewrite_suggestion' => $evaluation['rewrite_suggestion'],
            'grammar_issues' => $evaluation['grammar_issues'],
            'passed' => $evaluation['passed'],
            'required_vocabulary_usage' => $evaluation['required_vocabulary_usage'] ?? 0,
            'used_vocabulary_count' => $evaluation['used_vocabulary_count'] ?? 0,
            'vocabulary_target_met' => $evaluation['vocabulary_target_met'] ?? true,
            'missing_vocabulary_words' => $evaluation['missing_vocabulary_words'] ?? [],
        ]);
    }
}
