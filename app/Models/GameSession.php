<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'min_lesson_id',
        'start_time',
        'status',
        'current_question_index',
        'current_question_start_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'current_question_start_time' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function minLesson()
    {
        return $this->belongsTo(Lesson::class, 'min_lesson_id');
    }

    public function teams()
    {
        return $this->hasMany(GameTeam::class);
    }

    public function questions()
    {
        return $this->hasMany(GameQuestion::class)->orderBy('order');
    }

    public function currentQuestion()
    {
        // Helper to get the actual question object based on index
        return $this->questions()->skip($this->current_question_index)->first();
    }
}
