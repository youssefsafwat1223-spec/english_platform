<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WritingSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'writing_exercise_id',
        'user_id',
        'lesson_id',
        'answer_text',
        'word_count',
        'status',
        'overall_score',
        'grammar_score',
        'vocabulary_score',
        'coherence_score',
        'task_score',
        'grammar_feedback_json',
        'ai_feedback_json',
        'rewrite_text',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'grammar_feedback_json' => 'array',
            'ai_feedback_json' => 'array',
            'submitted_at' => 'datetime',
        ];
    }

    public function exercise()
    {
        return $this->belongsTo(WritingExercise::class, 'writing_exercise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
