<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class PopUpQuestionController extends Controller
{
    public function getRandom()
    {
        $user = auth()->user();
        
        // Find a random question from courses the user is enrolled in
        $question = \App\Models\Question::where(function ($q) {
                $q->whereNull('question_type')
                  ->orWhere('question_type', 'multiple_choice');
            })
            ->whereHas('course', function ($q) use ($user) {
                $q->whereIn('courses.id', $user->enrollments()->pluck('course_id'));
            })
            ->inRandomOrder()
            ->first();

        // Fallback if no specific course question is found
        if (!$question) {
            $question = \App\Models\Question::where(function ($q) {
                $q->whereNull('question_type')
                  ->orWhere('question_type', 'multiple_choice');
            })->inRandomOrder()->first();
        }

        if (!$question) {
            return response()->json(['success' => false]);
        }

        // Clean options to remove empty ones
        $options = array_values(array_filter($question->options ?? [], fn($opt) => strlen(trim((string)$opt)) > 0));

        return response()->json([
            'success' => true,
            'id' => $question->id,
            'text' => strip_tags($question->question_text),
            'options' => $options,
        ]);
    }

    public function checkAnswer(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);
        
        $isCorrect = $question->isCorrect($request->answer);
        
        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'correct_option_text' => $question->correct_option_text,
        ]);
    }
}
