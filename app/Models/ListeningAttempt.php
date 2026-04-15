<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListeningAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'listening_exercise_id',
        'user_id',
        'lesson_id',
        'course_level_id',
        'answers_json',
        'results_json',
        'score',
        'correct_count',
        'total_questions',
        'passed',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'answers_json' => 'array',
            'results_json' => 'array',
            'passed'       => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function exercise()
    {
        return $this->belongsTo(ListeningExercise::class, 'listening_exercise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class);
    }
}
