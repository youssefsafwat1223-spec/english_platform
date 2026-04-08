<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PronunciationAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pronunciation_exercise_id',
        'lesson_id',
        'attempt_number',
        'sentence_number',
        'audio_recording_path',
        'recording_duration',
        'overall_score',
        'clarity_score',
        'pronunciation_score',
        'fluency_score',
        'feedback_text',
        'ai_provider',
        'transcript_text',
        'expected_text',
        'word_diff_json',
        'completion_percent',
        'recognition_latency_ms',
        'stream_session_id',
        'azure_accuracy_score',
        'azure_fluency_score',
        'azure_completeness_score',
        'azure_pronunciation_score',
        'azure_prosody_score',
        'azure_response_json',
    ];

    protected function casts(): array
    {
        return [
            'word_diff_json' => 'array',
            'azure_response_json' => 'array',
            'completion_percent' => 'integer',
            'recognition_latency_ms' => 'integer',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pronunciationExercise()
    {
        return $this->belongsTo(PronunciationExercise::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get recording URL
     */
    public function getRecordingUrlAttribute()
    {
        return Storage::url($this->audio_recording_path);
    }

    /**
     * Get grade based on overall score
     */
    public function getGradeAttribute()
    {
        if ($this->overall_score >= 90) return 'Excellent';
        if ($this->overall_score >= 80) return 'Very Good';
        if ($this->overall_score >= 70) return 'Good';
        if ($this->overall_score >= 60) return 'Fair';
        return 'Needs Improvement';
    }

    /**
     * Check if passed
     */
    public function getPassedAttribute()
    {
        return $this->overall_score >= $this->pronunciationExercise->passing_score;
    }

    /**
     * Get star rating (out of 5)
     */
    public function getStarRatingAttribute()
    {
        return round(($this->overall_score / 100) * 5);
    }

    // ==================== METHODS ====================

    /**
     * Award points if passed
     */
    public function awardPoints()
    {
        if ($this->passed) {
            $this->user->addPoints(
                config('app.points_per_pronunciation', 10),
                'pronunciation_practice',
                $this->lesson_id,
                "Pronunciation practice: Sentence {$this->sentence_number} ({$this->overall_score}%)"
            );
        }
    }
}
