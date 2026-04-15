<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ListeningAttempt;
use App\Models\ListeningExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListeningController extends Controller
{
    public function show(ListeningExercise $listeningExercise)
    {
        $listeningExercise->load(['lesson', 'courseLevel']);

        $context = $listeningExercise->isSectionLevel()
            ? $listeningExercise->courseLevel
            : $listeningExercise->lesson;

        $lastAttempt = $listeningExercise->latestAttemptByUser(Auth::id());

        return view('student.listening.show', [
            'exercise'    => $listeningExercise,
            'context'     => $context,
            'lastAttempt' => $lastAttempt,
        ]);
    }

    public function submit(Request $request, ListeningExercise $listeningExercise)
    {
        $request->validate(['answers' => ['required', 'array']]);

        $questions = $listeningExercise->questions_json;
        $answers   = $request->input('answers');
        $results   = [];
        $correct   = 0;

        foreach ($questions as $index => $question) {
            $isCorrect = false;

            $studentAnswer = $answers[$index] ?? '';

            if ($question['type'] === 'mcq') {
                $isCorrect = (string)$studentAnswer === (string)$question['correct_index'];
                $correctDisplay = $question['options'][$question['correct_index']] ?? '';
            } elseif ($question['type'] === 'truefalse') {
                $isCorrect = strtolower(trim($studentAnswer)) === strtolower($question['correct']);
                $correctDisplay = $question['correct'];
            } elseif ($question['type'] === 'dictation') {
                $normalized = strtolower(trim((string)$studentAnswer));
                $correctAnswers = [strtolower(trim($question['correct_answer'] ?? ''))];
                foreach ($question['accept_variants'] ?? [] as $v) {
                    $correctAnswers[] = strtolower(trim($v));
                }
                $isCorrect = in_array($normalized, array_filter($correctAnswers));
                $correctDisplay = $question['correct_answer'] ?? '';
            } else {
                $isCorrect = false;
                $correctDisplay = '';
            }

            if ($isCorrect) $correct++;

            $results[] = [
                'correct'        => $isCorrect,
                'student_answer' => $studentAnswer,
                'correct_answer' => $correctDisplay,
                'explanation'    => $question['explanation'] ?? null,
            ];
        }

        $total  = count($questions);
        $score  = $total > 0 ? (int)round(($correct / $total) * 100) : 0;
        $passed = $score >= $listeningExercise->passing_score;

        ListeningAttempt::create([
            'listening_exercise_id' => $listeningExercise->id,
            'user_id'               => Auth::id(),
            'lesson_id'             => $listeningExercise->lesson_id,
            'course_level_id'       => $listeningExercise->course_level_id,
            'answers_json'          => $answers,
            'results_json'          => $results,
            'score'                 => $score,
            'correct_count'         => $correct,
            'total_questions'       => $total,
            'passed'                => $passed,
            'submitted_at'          => now(),
        ]);

        return response()->json([
            'score'         => $score,
            'correct'       => $correct,
            'total'         => $total,
            'passed'        => $passed,
            'passing_score' => $listeningExercise->passing_score,
            'results'       => $results,
        ]);
    }
}
