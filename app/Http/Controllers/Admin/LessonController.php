<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Http\Requests\StoreLessonRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons()
            ->orderBy('order_index')
            ->get();

        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        $availableQuizzes = $course->quizzes()
            ->where('quiz_type', 'lesson')
            ->whereNull('lesson_id')
            ->orderBy('title')
            ->get();

        $availableQuestions = $course->questions()
            ->orderBy('id', 'desc')
            ->get();

        $levels = $course->levels()->ordered()->get();

        return view('admin.lessons.create', compact('course', 'availableQuizzes', 'availableQuestions', 'levels'));
    }

    public function store(StoreLessonRequest $request, Course $course)
    {
        $data = $request->validated();
        
        // Generate slug
        $data['slug'] = Str::slug($data['title']);
        
        // Set order index
        if (!isset($data['order_index'])) {
            $data['order_index'] = $course->lessons()->max('order_index') + 1;
        }

        $lesson = $course->lessons()->create($data);

        $this->syncLessonQuiz($request, $course, $lesson);
        $this->syncPronunciationExercise($request, $lesson);
        $this->syncWritingExercise($request, $lesson);
        $this->syncListeningExercise($request, $lesson);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("courses/{$course->id}/lessons/{$lesson->id}/attachments", 'public');
                
                $lesson->attachments()->create([
                    'title' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => round($file->getSize() / 1024), // KB
                ]);
            }
        }

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', __('تم إنشاء الدرس بنجاح!'));
    }

    public function show(Course $course, Lesson $lesson)
    {
        $lesson->load(['attachments', 'audio', 'quiz', 'pronunciationExercise', 'comments']);

        $stats = [
            'total_comments' => $lesson->comments()->count(),
            'total_notes' => $lesson->notes()->count(),
            'completion_rate' => $this->calculateCompletionRate($lesson),
        ];

        $previousLesson = $course->lessons()
            ->where('order_index', '<', $lesson->order_index)
            ->orderBy('order_index', 'desc')
            ->first();

        $nextLesson = $course->lessons()
            ->where('order_index', '>', $lesson->order_index)
            ->orderBy('order_index', 'asc')
            ->first();

        return view('admin.lessons.show', compact('course', 'lesson', 'stats', 'previousLesson', 'nextLesson'));
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $lesson->load('attachments', 'listeningExercise');
        $availableQuizzes = $course->quizzes()
            ->where('quiz_type', 'lesson')
            ->where(function ($query) use ($lesson) {
                $query->whereNull('lesson_id')
                    ->orWhere('lesson_id', $lesson->id);
            })
            ->orderBy('title')
            ->get();

        $availableQuestions = $course->questions()
            ->orderBy('id', 'desc')
            ->get();

        $selectedQuestionIds = $lesson->quiz
            ? $lesson->quiz->questions()->pluck('questions.id')->toArray()
            : [];

        $pronunciationExercise = $lesson->pronunciationExercise;
        $writingExercise = $lesson->writingExercise;
        $listeningExercise = $lesson->listeningExercise;

        $levels = $course->levels()->ordered()->get();

        $previousLesson = $course->lessons()
            ->where('order_index', '<', $lesson->order_index)
            ->orderBy('order_index', 'desc')
            ->first();

        $nextLesson = $course->lessons()
            ->where('order_index', '>', $lesson->order_index)
            ->orderBy('order_index', 'asc')
            ->first();

        return view('admin.lessons.edit', compact(
            'course',
            'lesson',
            'availableQuizzes',
            'availableQuestions',
            'selectedQuestionIds',
            'pronunciationExercise',
            'writingExercise',
            'listeningExercise',
            'levels',
            'previousLesson',
            'nextLesson'
        ));
    }

    public function update(StoreLessonRequest $request, Course $course, Lesson $lesson)
    {
        $data = $request->validated();

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $lesson->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        $lesson->update($data);

        $this->syncLessonQuiz($request, $course, $lesson);
        $this->syncPronunciationExercise($request, $lesson);
        $this->syncWritingExercise($request, $lesson);
        $this->syncListeningExercise($request, $lesson);

        // Delete selected attachments
        if ($request->has('delete_attachments')) {
            $attachmentsToDelete = $lesson->attachments()->whereIn('id', $request->input('delete_attachments'))->get();
            foreach ($attachmentsToDelete as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            }
        }

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("courses/{$course->id}/lessons/{$lesson->id}/attachments", 'public');
                
                $lesson->attachments()->create([
                    'title' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => round($file->getSize() / 1024),
                ]);
            }
        }

        return redirect()->route('admin.courses.lessons.show', [$course, $lesson])
            ->with('success', __('تم تعديل الدرس بنجاح!'));
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        // Delete attachments
        foreach ($lesson->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $lesson->delete();

        return redirect()->route('admin.courses.lessons.index', $course)
            ->with('success', __('تم حذف الدرس بنجاح!'));
    }

    public function reorder(Course $course)
    {
        $validated = request()->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        foreach ($validated['order'] as $index => $lessonId) {
            $course->lessons()->where('id', $lessonId)
                ->update(['order_index' => $index]);
        }

        return response()->json(['success' => true]);
    }

    private function calculateCompletionRate(Lesson $lesson)
    {
        $totalEnrollments = $lesson->course->enrollments()->count();

        if ($totalEnrollments === 0) {
            return 0;
        }

        $completedCount = $lesson->progressRecords()
            ->where('is_completed', true)
            ->count();

        return round(($completedCount / $totalEnrollments) * 100, 2);
    }

    private function syncLessonQuiz(StoreLessonRequest $request, Course $course, Lesson $lesson): void
    {
        if (!$request->boolean('has_quiz')) {
            if ($lesson->quiz) {
                $lesson->quiz->update(['lesson_id' => null]);
            }

            return;
        }

        $quizMode = $request->input('quiz_mode', 'questions');

        if ($quizMode === 'existing' && $request->filled('quiz_id')) {
            $quiz = Quiz::where('id', $request->quiz_id)
                ->where('course_id', $course->id)
                ->where('quiz_type', 'lesson')
                ->first();

            if ($quiz) {
                if ($lesson->quiz && $lesson->quiz->id !== $quiz->id) {
                    $lesson->quiz->update(['lesson_id' => null]);
                }

                $quiz->update(['lesson_id' => $lesson->id]);
            }

            return;
        }

        $questionIds = collect($request->input('question_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $questionIds = Question::whereIn('id', $questionIds)
            ->where('course_id', $course->id)
            ->pluck('id')
            ->values();

        if ($questionIds->isEmpty()) {
            return;
        }

        $quizTitle = $request->input('quiz_title') ?: "{$lesson->title} Quiz";
        $quizDuration = (int) $request->input('quiz_duration_minutes', 10);
        $quizPassing = (int) $request->input('quiz_passing_score', 70);

        $quizData = [
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => $quizTitle,
            'quiz_type' => 'lesson',
            'description' => null,
            'total_questions' => $questionIds->count(),
            'duration_minutes' => $quizDuration,
            'passing_score' => $quizPassing,
            'allow_retake' => $request->boolean('quiz_allow_retake'),
            'show_results_immediately' => $request->boolean('quiz_show_results'),
            'enable_audio' => $request->boolean('quiz_enable_audio'),
        ];

        $quiz = $lesson->quiz;

        if ($quiz) {
            $quiz->update($quizData);
        } else {
            $quiz = Quiz::create($quizData);
        }

        $syncData = [];
        foreach ($questionIds as $index => $questionId) {
            $syncData[$questionId] = ['order_index' => $index + 1];
        }

        $quiz->questions()->sync($syncData);

        Question::whereIn('id', $questionIds)->update(['lesson_id' => $lesson->id]);
    }

    private function syncPronunciationExercise(StoreLessonRequest $request, Lesson $lesson): void
    {
        if (!$request->boolean('has_pronunciation_exercise')) {
            if ($lesson->pronunciationExercise) {
                $this->deletePronunciationAudioFiles($lesson->pronunciationExercise);
                $lesson->pronunciationExercise()->delete();
            }

            return;
        }

        $exerciseData = [
            'sentence_1' => $request->input('pronunciation_sentence_1'),
            'sentence_2' => $request->input('pronunciation_sentence_2'),
            'sentence_3' => $request->input('pronunciation_sentence_3'),
            'vocabulary_json' => $this->parseVocabularyLines($request->input('pronunciation_vocabulary_lines')),
            'sentence_explanation' => $request->input('pronunciation_sentence_explanation'),
            'passage_explanation' => $request->input('pronunciation_passage_explanation'),
            'passing_score' => (int) $request->input('pronunciation_passing_score', 70),
            'max_duration_seconds' => (int) $request->input('pronunciation_max_duration', 10),
            'allow_retake' => $request->boolean('pronunciation_allow_retake'),
        ];

        if ($lesson->pronunciationExercise) {
            $lesson->pronunciationExercise->update($exerciseData);
        } else {
            $lesson->pronunciationExercise()->create($exerciseData);
        }

        $exercise = $lesson->pronunciationExercise()->first();

        if (!$exercise) {
            return;
        }

        $this->replacePronunciationAudioFiles($request, $lesson, $exercise);
    }

    private function syncWritingExercise(StoreLessonRequest $request, Lesson $lesson): void
    {
        if (!$request->boolean('has_writing_exercise')) {
            $lesson->writingExercise()?->delete();
            return;
        }

        $existingRubric = is_array($lesson->writingExercise?->rubric_json)
            ? $lesson->writingExercise->rubric_json
            : [];
        $rubric = [
            'grammar' => 25,
            'vocabulary' => 25,
            'coherence' => 25,
            'task_completion' => 25,
        ];

        if (isset($existingRubric['required_vocabulary_usage'])) {
            $rubric['required_vocabulary_usage'] = max(0, (int) $existingRubric['required_vocabulary_usage']);
        }

        if (isset($existingRubric['lesson_vocabulary']) && is_array($existingRubric['lesson_vocabulary'])) {
            $rubric['lesson_vocabulary'] = array_values($existingRubric['lesson_vocabulary']);
        }

        $exerciseData = [
            'title' => $request->input('writing_title'),
            'prompt' => $request->input('writing_prompt'),
            'instructions' => $request->input('writing_instructions'),
            'min_words' => (int) $request->input('writing_min_words', 30),
            'max_words' => (int) $request->input('writing_max_words', 180),
            'passing_score' => (int) $request->input('writing_passing_score', 70),
            'model_answer' => $request->input('writing_model_answer'),
            'rubric_json' => $rubric,
        ];

        if ($lesson->writingExercise) {
            $lesson->writingExercise->update($exerciseData);
            return;
        }

        $lesson->writingExercise()->create($exerciseData);
    }

    private function parseVocabularyLines(?string $lines): ?array
    {
        if (!$lines) {
            return null;
        }

        $items = collect(preg_split('/\r\n|\r|\n/', $lines))
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->map(function (string $line) {
                $parts = array_map('trim', explode('|', $line));

                return [
                    'word' => $parts[0] ?? '',
                    'pronunciation' => $parts[1] ?? '',
                    'meaning_ar' => $parts[2] ?? '',
                ];
            })
            ->filter(fn (array $item) => $item['word'] !== '')
            ->values()
            ->all();

        return empty($items) ? null : $items;
    }

    private function replacePronunciationAudioFiles(StoreLessonRequest $request, Lesson $lesson, $exercise): void
    {
        foreach ([1, 2, 3] as $index) {
            $field = "pronunciation_reference_audio_{$index}";
            $column = "reference_audio_{$index}";

            if (!$request->hasFile($field)) {
                continue;
            }

            if ($exercise->{$column}) {
                Storage::disk('public')->delete($exercise->{$column});
            }

            $path = $request->file($field)->store(
                "courses/{$lesson->course_id}/lessons/{$lesson->id}/pronunciation",
                'public'
            );

            $exercise->update([$column => $path]);
        }
    }

    private function deletePronunciationAudioFiles($exercise): void
    {
        foreach ([1, 2, 3] as $index) {
            $column = "reference_audio_{$index}";

            if ($exercise->{$column}) {
                Storage::disk('public')->delete($exercise->{$column});
            }
        }
    }

    private function syncListeningExercise(mixed $request, Lesson $lesson): void
    {
        if (!$request->boolean('has_listening_exercise')) {
            $lesson->listeningExercise()?->delete();
            return;
        }

        $questionsJson = $request->input('listening_questions_json');
        $questions     = [];

        if ($questionsJson) {
            $decoded = json_decode($questionsJson, true);
            if (is_array($decoded)) {
                $questions = $decoded;
            }
        }

        $exerciseData = [
            'lesson_id'       => $lesson->id,
            'course_level_id' => null,
            'title'           => $request->input('listening_title', $lesson->title),
            'script_ar'       => $request->input('listening_script_ar', ''),
            'questions_json'  => $questions,
            'passing_score'   => (int) $request->input('listening_passing_score', 70),
        ];

        if ($lesson->listeningExercise) {
            $lesson->listeningExercise->update($exerciseData);
        } else {
            \App\Models\ListeningExercise::create($exerciseData);
        }
    }
}
