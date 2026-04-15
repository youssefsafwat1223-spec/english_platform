<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\ListeningExercise;
use App\Models\WritingExercise;
use App\Models\PronunciationExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseLevelController extends Controller
{
    public function index(Course $course)
    {
        $levels = $course->levels()->withCount('lessons')->get();

        return view('admin.course-levels.index', compact('course', 'levels'));
    }

    public function create(Course $course)
    {
        return view('admin.course-levels.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'order_index' => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $data['slug']      = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active');

        if (!isset($data['order_index'])) {
            $data['order_index'] = $course->levels()->max('order_index') + 1;
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store("courses/{$course->id}/levels", 'public');
        }

        $course->levels()->create($data);

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', __('تم إضافة المستوى بنجاح!'));
    }

    public function edit(Course $course, CourseLevel $level)
    {
        $level->load('listeningExercise', 'writingExercise', 'speakingExercise');

        return view('admin.course-levels.edit', compact('course', 'level'));
    }

    public function update(Request $request, Course $course, CourseLevel $level)
    {
        $data = $request->validate([
            'title'                  => 'required|string|max:255',
            'description'            => 'nullable|string',
            'thumbnail'              => 'nullable|image|max:2048',
            'order_index'            => 'nullable|integer|min:0',
            'is_active'              => 'nullable|boolean',
            'has_writing_exercise'   => 'nullable|boolean',
            'has_speaking_exercise'  => 'nullable|boolean',
            'has_listening_exercise' => 'nullable|boolean',
        ]);

        $data['is_active']              = $request->boolean('is_active');
        $data['has_writing_exercise']   = $request->boolean('has_writing_exercise');
        $data['has_speaking_exercise']  = $request->boolean('has_speaking_exercise');
        $data['has_listening_exercise'] = $request->boolean('has_listening_exercise');

        if (isset($data['title']) && $data['title'] !== $level->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('thumbnail')) {
            if ($level->thumbnail) {
                Storage::disk('public')->delete($level->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store("courses/{$course->id}/levels", 'public');
        }

        $level->update($data);

        $this->syncWritingExercise($request, $level);
        $this->syncSpeakingExercise($request, $level);
        $this->syncListeningExercise($request, $level);

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', __('تم تعديل المستوى بنجاح!'));
    }

    public function destroy(Course $course, CourseLevel $level)
    {
        if ($level->thumbnail) {
            Storage::disk('public')->delete($level->thumbnail);
        }

        $level->load('speakingExercise');
        if ($level->speakingExercise) {
            foreach ([1, 2, 3] as $i) {
                $col = "reference_audio_{$i}";
                if ($level->speakingExercise->{$col}) {
                    Storage::disk('public')->delete($level->speakingExercise->{$col});
                }
            }
            $level->speakingExercise->delete();
        }

        $level->writingExercise()?->delete();
        $level->listeningExercise()?->delete();
        $level->delete();

        return redirect()->route('admin.courses.levels.index', $course)
            ->with('success', __('تم حذف المستوى بنجاح!'));
    }

    // ─── private sync helpers ────────────────────────────────────────

    private function syncWritingExercise(Request $request, CourseLevel $level): void
    {
        if (!$request->boolean('has_writing_exercise')) {
            $level->writingExercise()?->delete();
            return;
        }

        $exerciseData = [
            'course_level_id' => $level->id,
            'lesson_id'       => null,
            'title'           => $request->input('writing_title', $level->title),
            'prompt'          => $request->input('writing_prompt', ''),
            'instructions'    => $request->input('writing_instructions'),
            'min_words'       => (int) $request->input('writing_min_words', 50),
            'max_words'       => (int) $request->input('writing_max_words', 200),
            'passing_score'   => (int) $request->input('writing_passing_score', 70),
            'model_answer'    => $request->input('writing_model_answer'),
            'rubric_json'     => [
                'grammar'         => 25,
                'vocabulary'      => 25,
                'coherence'       => 25,
                'task_completion' => 25,
            ],
        ];

        if ($level->writingExercise) {
            $level->writingExercise->update($exerciseData);
        } else {
            WritingExercise::create($exerciseData);
        }
    }

    private function syncSpeakingExercise(Request $request, CourseLevel $level): void
    {
        if (!$request->boolean('has_speaking_exercise')) {
            if ($level->speakingExercise) {
                foreach ([1, 2, 3] as $i) {
                    $col = "reference_audio_{$i}";
                    if ($level->speakingExercise->{$col}) {
                        Storage::disk('public')->delete($level->speakingExercise->{$col});
                    }
                }
                $level->speakingExercise->delete();
            }
            return;
        }

        $exerciseData = [
            'course_level_id'     => $level->id,
            'lesson_id'           => null,
            'sentence_1'          => $request->input('speaking_sentence_1'),
            'sentence_2'          => $request->input('speaking_sentence_2'),
            'sentence_3'          => $request->input('speaking_sentence_3'),
            'passing_score'       => (int) $request->input('speaking_passing_score', 70),
            'max_duration_seconds' => (int) $request->input('speaking_max_duration', 15),
            'allow_retake'        => $request->boolean('speaking_allow_retake'),
            'sentence_explanation' => $request->input('speaking_sentence_explanation'),
            'passage_explanation'  => $request->input('speaking_passage_explanation'),
            'vocabulary_json'     => $this->parseVocabularyLines($request->input('speaking_vocabulary_lines')),
        ];

        if ($level->speakingExercise) {
            $level->speakingExercise->update($exerciseData);
            $exercise = $level->speakingExercise;
        } else {
            $exercise = PronunciationExercise::create($exerciseData);
        }

        // Handle audio uploads
        foreach ([1, 2, 3] as $i) {
            $field = "speaking_reference_audio_{$i}";
            $col   = "reference_audio_{$i}";
            if ($request->hasFile($field)) {
                if ($exercise->{$col}) {
                    Storage::disk('public')->delete($exercise->{$col});
                }
                $path = $request->file($field)->store(
                    "courses/{$level->course_id}/levels/{$level->id}/speaking",
                    'public'
                );
                $exercise->update([$col => $path]);
            }
        }
    }

    private function syncListeningExercise(Request $request, CourseLevel $level): void
    {
        if (!$request->boolean('has_listening_exercise')) {
            $level->listeningExercise()?->delete();
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
            'course_level_id' => $level->id,
            'lesson_id'       => null,
            'title'           => $request->input('listening_title', $level->title),
            'script_ar'       => $request->input('listening_script_ar', ''),
            'questions_json'  => $questions,
            'passing_score'   => (int) $request->input('listening_passing_score', 70),
        ];

        if ($level->listeningExercise) {
            $level->listeningExercise->update($exerciseData);
        } else {
            ListeningExercise::create($exerciseData);
        }
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
                    'word'         => $parts[0] ?? '',
                    'pronunciation' => $parts[1] ?? '',
                    'meaning_ar'   => $parts[2] ?? '',
                ];
            })
            ->filter(fn (array $item) => $item['word'] !== '')
            ->values()
            ->all();

        return empty($items) ? null : $items;
    }
}
