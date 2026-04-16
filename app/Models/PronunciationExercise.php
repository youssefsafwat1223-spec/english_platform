<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PronunciationExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'course_level_id',
        'sentence_1',
        'sentence_2',
        'sentence_3',
        'sentences_json',
        'reference_audio_1',
        'reference_audio_2',
        'reference_audio_3',
        'passing_score',
        'max_duration_seconds',
        'allow_retake',
        'vocabulary_json',
        'passage_explanation',
        'sentence_explanation',
    ];

    protected function casts(): array
    {
        return [
            'allow_retake'    => 'boolean',
            'vocabulary_json' => 'array',
            'sentences_json'  => 'array',
        ];
    }

    /**
     * Get vocabulary list (array of {word, pronunciation, meaning_ar})
     */
    public function getVocabularyAttribute(): array
    {
        return $this->vocabulary_json ?? [];
    }

    // ==================== RELATIONSHIPS ====================

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function attempts()
    {
        return $this->hasMany(PronunciationAttempt::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get all sentences as array (fixed fields + JSON overflow)
     */
    public function getSentencesAttribute()
    {
        $sentences = [];

        if ($this->sentence_1) {
            $sentences[1] = $this->sentence_1;
        }

        if ($this->sentence_2) {
            $sentences[2] = $this->sentence_2;
        }

        if ($this->sentence_3) {
            $sentences[3] = $this->sentence_3;
        }

        // Append extra sentences from sentences_json
        if (is_array($this->sentences_json)) {
            $nextKey = empty($sentences) ? 1 : max(array_keys($sentences)) + 1;
            foreach ($this->sentences_json as $s) {
                if (is_string($s) && trim($s) !== '') {
                    $sentences[$nextKey++] = trim($s);
                }
            }
        }

        return $sentences;
    }

    /**
     * Get reference audio URLs
     */
    public function getReferenceAudioUrlsAttribute()
    {
        $urls = [];

        if ($this->reference_audio_1) {
            $urls[1] = Storage::url($this->reference_audio_1);
        }

        if ($this->reference_audio_2) {
            $urls[2] = Storage::url($this->reference_audio_2);
        }

        if ($this->reference_audio_3) {
            $urls[3] = Storage::url($this->reference_audio_3);
        }

        return $urls;
    }

    /**
     * Get total sentences count
     */
    public function getTotalSentencesAttribute()
    {
        return count($this->sentences);
    }

    // ==================== METHODS ====================

    /**
     * Get user's best attempt
     */
    public function getBestAttempt(User $user)
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->orderBy('overall_score', 'desc')
            ->first();
    }

    /**
     * Get user's attempts for specific sentence
     */
    public function getSentenceAttempts(User $user, $sentenceNumber)
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->where('sentence_number', $sentenceNumber)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user can attempt
     */
    public function canUserAttempt(User $user)
    {
        if ($this->allow_retake) {
            return true;
        }

        return $this->attempts()
            ->where('user_id', $user->id)
            ->doesntExist();
    }
}