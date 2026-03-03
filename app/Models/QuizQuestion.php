<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class QuizQuestion extends Pivot
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'quiz_id',
        'question_id',
        'order_index',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}