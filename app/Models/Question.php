<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'lesson_id',
        'question_text',
        'question_type',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'difficulty',
        'points',
        'has_audio',
        'audio_path',
        'audio_duration',
        'tts_settings',
    ];

    protected function casts(): array
    {
        return [
            'has_audio' => 'boolean',
            'tts_settings' => 'array',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_questions')
            ->withTimestamps()
            ->withPivot('order_index');
    }

    public function dailyQuestions()
    {
        return $this->hasMany(DailyQuestion::class);
    }

    public function quizAnswers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    // ==================== SCOPES ====================

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('question_type', $type);
    }

    public function scopeWithAudio($query)
    {
        return $query->where('has_audio', true);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get audio URL
     */
    public function getAudioUrlAttribute()
    {
        if (!$this->has_audio || !$this->audio_path) {
            return null;
        }

        return Storage::url($this->audio_path);
    }

    /**
     * Get all options as array
     */
    public function getOptionsAttribute()
    {
        return [
            'A' => $this->option_a,
            'B' => $this->option_b,
            'C' => $this->option_c,
            'D' => $this->option_d,
        ];
    }

    /**
     * Get correct option text
     */
    public function getCorrectOptionTextAttribute()
    {
        return $this->{'option_' . strtolower($this->correct_answer)};
    }

    // ==================== METHODS ====================

    /**
     * Check if answer is correct
     */
    public function isCorrect($answer)
    {
        return strtoupper($answer) === strtoupper($this->correct_answer);
    }

    /**
     * Generate TTS audio text
     */
    public function getTTSText()
    {
        $text = "Question. {$this->question_text}. ";
        $text .= "Option A: {$this->option_a}. ";
        $text .= "Option B: {$this->option_b}. ";

        if ($this->option_c) {
            $text .= "Option C: {$this->option_c}. ";
        }

        if ($this->option_d) {
            $text .= "Option D: {$this->option_d}.";
        }

        return $text;
    }
}