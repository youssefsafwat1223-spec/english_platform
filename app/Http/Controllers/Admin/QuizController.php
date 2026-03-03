<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Http\Requests\StoreQuizRequest;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with(['course', 'lesson'])
            ->withCount('attempts')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::all();
        $lessons = Lesson::all();
        $lessonsForJs = $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'course_id' => $lesson->course_id,
                'title' => $lesson->title,
            ];
        })->values();

        return view('admin.quizzes.create', compact('courses', 'lessons', 'lessonsForJs'));
    }

    public function store(StoreQuizRequest $request)
    {
        $data = $request->validated();
        
        $questionIds = $data['questions'];
        unset($data['questions']);

        $data['total_questions'] = count($questionIds);

        $quiz = Quiz::create($data);

        // Attach questions with order
        foreach ($questionIds as $index => $questionId) {
            $quiz->questions()->attach($questionId, [
                'order_index' => $index,
            ]);
        }

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully!');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['course', 'lesson', 'questions', 'attempts.user']);

        $stats = [
            'total_attempts' => $quiz->attempts()->count(),
            'unique_students' => $quiz->attempts()->distinct('user_id')->count('user_id'),
            'pass_rate' => $quiz->getPassRate(),
            'average_score' => $quiz->getAverageScore(),
            'average_time' => $quiz->attempts()->avg('time_taken'),
        ];

        return view('admin.quizzes.show', compact('quiz', 'stats'));
    }

    public function edit(Quiz $quiz)
    {
        $courses = Course::all();
        $lessons = Lesson::all();
        $quiz->load('questions');
        $lessonsForJs = $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'course_id' => $lesson->course_id,
                'title' => $lesson->title,
            ];
        })->values();

        return view('admin.quizzes.edit', compact('quiz', 'courses', 'lessons', 'lessonsForJs'));
    }

    public function update(StoreQuizRequest $request, Quiz $quiz)
    {
        $data = $request->validated();
        
        $questionIds = $data['questions'];
        unset($data['questions']);

        $data['total_questions'] = count($questionIds);

        $quiz->update($data);

        // Sync questions
        $quiz->questions()->detach();
        
        foreach ($questionIds as $index => $questionId) {
            $quiz->questions()->attach($questionId, [
                'order_index' => $index,
            ]);
        }

        return redirect()->route('admin.quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }

    public function getQuestions(Course $course, Lesson $lesson = null)
    {
        $query = Question::where('course_id', $course->id);

        if ($lesson) {
            $query->where('lesson_id', $lesson->id);
        }

        $questions = $query->get();

        return response()->json($questions);
    }

    public function getCourseQuestions(Course $course)
    {
        $questions = Question::where('course_id', $course->id)->get();

        return response()->json($questions);
    }

    public function attempts(Quiz $quiz)
    {
        $attempts = $quiz->attempts()
            ->with(['user', 'answers.question'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.quizzes.attempts', compact('quiz', 'attempts'));
    }

    public function attemptDetails(Quiz $quiz, $attemptId)
    {
        $attempt = $quiz->attempts()
            ->with(['user', 'answers.question'])
            ->findOrFail($attemptId);

        return view('admin.quizzes.attempt-details', compact('quiz', 'attempt'));
    }
}
