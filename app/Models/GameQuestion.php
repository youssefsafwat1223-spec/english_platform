<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_session_id',
        'question_text',
        'image_url',
        'time_limit_seconds',
        'points',
        'options',
        'correct_answer',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function answers()
    {
        return $this->hasMany(GameAnswer::class);
    }
}
