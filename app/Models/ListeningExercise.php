<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'course_level_id',
        'title',
        'script_ar',
        'script_display',
        'audio_path',
        'audio_url',
        'questions_json',
        'passing_score',
        'audio_generated',
    ];

    protected function casts(): array
    {
        return [
            'questions_json'  => 'array',
            'audio_generated' => 'boolean',
        ];
    }

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
        return $this->hasMany(ListeningAttempt::class);
    }

    public function latestAttemptByUser(int $userId)
    {
        return $this->attempts()->where('user_id', $userId)->latest()->first();
    }

    /** هل الاختبار على مستوى عنوان؟ */
    public function isSectionLevel(): bool
    {
        return !is_null($this->course_level_id);
    }
}
