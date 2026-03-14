<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Services\TTSService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private $ttsService;

    public function __construct(TTSService $ttsService)
    {
        $this->ttsService = $ttsService;
    }

    public function index(Request $request)
    {
        $query = Question::with(['course', 'lesson'])
            ->withCount('quizzes');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('question_text', 'like', "%{$search}%");
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('type')) {
            $query->where('question_type', $request->type);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }

        $questions = $query->orderBy('created_at', 'desc')
            ->paginate(50)
            ->appends($request->query());

        $courses = Course::orderBy('title')->get();
        $lessons = Lesson::orderBy('title')->get();

        return view('admin.questions.index', compact('questions', 'courses', 'lessons'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        $lessons = Lesson::orderBy('title')->get();

        return view('admin.questions.create', compact('courses', 'lessons'));
    }

    public function store(StoreQuestionRequest $request)
    {
        $data = $request->validated();

        // Handle custom audio upload
        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')
                ->store('quiz-audio/custom', 'public');
            
            $data['has_audio'] = true;
            $data['audio_path'] = $path;
        }

        $question = Question::create($data);

        // Generate TTS audio if no custom audio
        if (!$request->hasFile('audio_file') && $request->input('generate_tts')) {
            $result = $this->ttsService->generateQuestionAudio($question);

            if (!$result['success']) {
                return redirect()->route('admin.questions.index')
                    ->with('success', __('تم إنشاء السؤال بنجاح!'))
                    ->with('error', __('فشل في إنشاء الصوت: ') . $result['error']);
            }
        }

        return redirect()->route('admin.questions.index')
            ->with('success', __('تم إنشاء السؤال بنجاح!'));
    }

    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,fill_blank,drag_drop',
            'option_a' => 'required_unless:question_type,drag_drop|nullable|string|max:500',
            'option_b' => 'required_if:question_type,multiple_choice,true_false|nullable|string|max:500',
            'option_c' => 'nullable|string|max:500',
            'option_d' => 'nullable|string|max:500',
            'matching_pairs' => 'required_if:question_type,drag_drop|nullable|array|min:2',
            'matching_pairs.*.left' => 'required_with:matching_pairs|string',
            'matching_pairs.*.right' => 'required_with:matching_pairs|string',
            'correct_answer' => 'required_unless:question_type,drag_drop|nullable|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'points' => 'nullable|integer|min:1',
        ]);

        // For fill_blank, correct_answer is always A (option_a holds the answer)
        if ($validated['question_type'] === 'fill_blank') {
            $validated['correct_answer'] = 'A';
        }

        // For drag_drop, set a default correct_answer since it's not used
        if ($validated['question_type'] === 'drag_drop') {
            $validated['correct_answer'] = 'A';
        }
        $question = Question::create($validated);

        return response()->json([
            'success' => true,
            'question' => [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'difficulty' => $question->difficulty,
                'question_type' => $question->question_type,
            ],
        ]);
    }

    public function show(Question $question)
    {
        $question->load(['course', 'lesson', 'quizzes']);

        $stats = [
            'total_attempts' => $question->quizAnswers()->count(),
            'correct_answers' => $question->quizAnswers()->where('is_correct', true)->count(),
            'used_in_quizzes' => $question->quizzes()->count(),
            'used_in_daily_questions' => $question->dailyQuestions()->count(),
        ];

        return view('admin.questions.show', compact('question', 'stats'));
    }

    public function edit(Question $question)
    {
        $courses = Course::orderBy('title')->get();
        $lessons = Lesson::orderBy('title')->get();

        return view('admin.questions.edit', compact('question', 'courses', 'lessons'));
    }

    public function update(StoreQuestionRequest $request, Question $question)
    {
        $data = $request->validated();

        // Handle custom audio upload
        if ($request->hasFile('audio_file')) {
            // Delete old audio
            if ($question->audio_path) {
                Storage::disk('public')->delete($question->audio_path);
            }

            $path = $request->file('audio_file')
                ->store('quiz-audio/custom', 'public');
            
            $data['has_audio'] = true;
            $data['audio_path'] = $path;
        }

        $question->update($data);

        // Regenerate TTS if requested
        if ($request->input('regenerate_tts')) {
            if ($question->audio_path && Str::contains($question->audio_path, 'tts-generated')) {
                Storage::disk('public')->delete($question->audio_path);
            }
            
            $result = $this->ttsService->generateQuestionAudio($question);

            if (!$result['success']) {
                return redirect()->route('admin.questions.show', $question)
                    ->with('error', __('فشل في إنشاء الصوت: ') . $result['error']);
            }
        }

        return redirect()->route('admin.questions.show', $question)
            ->with('success', __('تم تحديث السؤال بنجاح!'));
    }

    public function destroy(Question $question)
    {
        // Delete audio file
        if ($question->audio_path) {
            Storage::disk('public')->delete($question->audio_path);
        }

        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', __('تم حذف السؤال بنجاح!'));
    }

    public function generateAudio(Question $question)
    {
        $result = $this->ttsService->generateQuestionAudio($question);

        if ($result['success']) {
            return back()->with('success', __('تم إنشاء الصوت بنجاح!'));
        }

        return back()->with('error', __('فشل في إنشاء الصوت: ') . $result['error']);
    }

    public function deleteAudio(Question $question)
    {
        if ($question->audio_path) {
            Storage::disk('public')->delete($question->audio_path);
            
            $question->update([
                'has_audio' => false,
                'audio_path' => null,
                'audio_duration' => null,
            ]);

            return back()->with('success', __('تم حذف الصوت بنجاح!'));
        }

        return back()->with('error', __('لا يوجد ملف صوتي للحذف'));
    }

    public function import()
    {
        // Import questions from Excel/CSV
        return view('admin.questions.import');
    }

    public function reference()
    {
        $courses = Course::with('lessons')->orderBy('title')->get();
        return view('admin.questions.reference', compact('courses'));
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
            'course_id' => 'nullable|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->path(), 'r');
        
        // Read headers
        $headers = fgetcsv($handle, 1000, ',');
        
        if (!$headers) {
            return back()->with('error', __('Invalid or empty CSV file.'));
        }

        // Clean headers (remove BOM, lowercase, trim whitespace)
        $headers = array_map(function($header) {
            $header = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header);
            return trim(strtolower($header));
        }, $headers);

        $expectedHeaders = [
            'course_id', 'lesson_id', 'question_text', 'question_type', 'difficulty', 
            'option_a', 'option_b', 'option_c', 'option_d', 
            'correct_answer', 'explanation'
        ];

        // Check if all expected headers are present
        foreach ($expectedHeaders as $expected) {
            if (!in_array($expected, $headers)) {
                fclose($handle);
                return back()->with('error', __('Missing required column in CSV:') . ' ' . $expected);
            }
        }

        $successCount = 0;
        $errorCount = 0;
        $row = 2; // Start from row 2 (assuming row 1 is headers)
        $errors = [];

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Map data to headers
            $rowAssoc = [];
            foreach ($headers as $index => $header) {
                $rowAssoc[$header] = $data[$index] ?? null;
            }

            // Prepare question data
            $questionData = [
                'course_id' => !empty($rowAssoc['course_id']) ? $rowAssoc['course_id'] : $request->course_id,
                'lesson_id' => !empty($rowAssoc['lesson_id']) ? $rowAssoc['lesson_id'] : $request->lesson_id,
                'question_text' => $rowAssoc['question_text'],
                'question_type' => strtolower($rowAssoc['question_type'] ?? 'multiple_choice'),
                'difficulty' => strtolower($rowAssoc['difficulty'] ?? 'medium'),
                'option_a' => $rowAssoc['option_a'],
                'option_b' => $rowAssoc['option_b'],
                'option_c' => $rowAssoc['option_c'],
                'option_d' => $rowAssoc['option_d'],
                'correct_answer' => strtoupper($rowAssoc['correct_answer'] ?? 'A'),
                'explanation' => $rowAssoc['explanation'] ?? null,
                'points' => 10, // Default points
                'has_audio' => false,
            ];

            // Validate the row
            $validator = Validator::make($questionData, [
                'question_text' => 'required|string',
                'question_type' => 'required|in:multiple_choice,true_false,fill_in_the_blank',
                'difficulty' => 'required|in:easy,medium,hard',
                'option_a' => 'required_unless:question_type,fill_in_the_blank|string|nullable',
                'option_b' => 'required_unless:question_type,fill_in_the_blank|string|nullable',
                'correct_answer' => 'required|string',
            ]);

            if ($validator->fails()) {
                $errorCount++;
                $errors[] = "Row {$row}: " . implode(', ', $validator->errors()->all());
            } else {
                try {
                    Question::create($questionData);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row {$row}: " . __('Failed to insert.') . ' ' . $e->getMessage();
                }
            }
            $row++;
        }

        fclose($handle);

        if ($errorCount > 0) {
            $message = __(':success questions imported successfully. :errors failed.', ['success' => $successCount, 'errors' => $errorCount]);
            return redirect()->route('admin.questions.index')
                ->with('success', $message)
                ->with('import_errors', $errors);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', __(':count questions imported successfully!', ['count' => $successCount]));
    }
}
