<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

class AnalyticsController extends Controller
{
    public function questions(Request $request)
    {
        // Get questions with their failure rate, sorted by most failed
        $questions = Question::withCount([
                'quizAnswers as total_attempts',
                'quizAnswers as total_failed' => function ($query) {
                    $query->where('is_correct', false);
                }
            ])
            ->having('total_attempts', '>', 0)
            ->with(['course', 'lesson']) // Eager load context
            ->orderByRaw('(SELECT COUNT(*) FROM quiz_answers WHERE quiz_answers.question_id = questions.id AND quiz_answers.is_correct = 0) / (SELECT COUNT(*) FROM quiz_answers WHERE quiz_answers.question_id = questions.id) DESC')
            ->orderBy('total_attempts', 'DESC')
            ->paginate(20);

        return view('admin.analytics.questions', compact('questions'));
    }
}
