<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WritingExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'prompt',
        'instructions',
        'min_words',
        'max_words',
        'passing_score',
        'model_answer',
        'rubric_json',
    ];

    protected function casts(): array
    {
        return [
            'rubric_json' => 'array',
        ];
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(WritingSubmission::class);
    }
}
