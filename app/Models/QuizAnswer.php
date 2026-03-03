<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_attempt_id',
        'question_id',
        'user_answer',
        'is_correct',
        'time_taken',
        'audio_played',
        'audio_replay_count',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'audio_played' => 'boolean',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get user answer text
     */
    public function getUserAnswerTextAttribute()
    {
        return $this->question->{'option_' . strtolower($this->user_answer)};
    }

    /**
     * Get correct answer text
     */
    public function getCorrectAnswerTextAttribute()
    {
        return $this->question->correct_option_text;
    }
}