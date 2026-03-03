<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LessonAudio extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'audio_path',
        'duration',
        'file_size',
    ];

    // ==================== RELATIONSHIPS ====================

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get audio URL
     */
    public function getAudioUrlAttribute()
    {
        return Storage::url($this->audio_path);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) {
            return null;
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}