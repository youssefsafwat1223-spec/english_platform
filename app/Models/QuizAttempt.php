<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'enrollment_id',
        'attempt_number',
        'score',
        'correct_answers',
        'total_questions',
        'time_taken',
        'passed',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    // ==================== SCOPES ====================

    public function scopePassed($query)
    {
        return $query->where('passed', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('passed', false);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get formatted time taken
     */
    public function getFormattedTimeAttribute()
    {
        $minutes = floor($this->time_taken / 60);
        $seconds = $this->time_taken % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get grade letter
     */
    public function getGradeAttribute()
    {
        if ($this->score >= 90) return 'A';
        if ($this->score >= 80) return 'B';
        if ($this->score >= 70) return 'C';
        if ($this->score >= 60) return 'D';
        return 'F';
    }

    /**
     * Get incorrect answers count
     */
    public function getIncorrectAnswersAttribute()
    {
        return $this->total_questions - $this->correct_answers;
    }

    // ==================== METHODS ====================

    /**
     * Check if attempt passed
     */
    public function checkPassed()
    {
        $this->passed = $this->score >= $this->quiz->passing_score;
        $this->save();

        return $this->passed;
    }

    /**
     * Award points if passed
     */
    public function awardPoints()
    {
        if ($this->passed) {
            $this->user->addPoints(
                config('app.points_per_quiz', 30),
                'quiz_pass',
                $this->quiz_id,
                "Passed quiz: {$this->quiz->title} ({$this->score}%)"
            );
        }
    }
}