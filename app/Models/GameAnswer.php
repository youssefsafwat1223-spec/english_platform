<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_question_id',
        'game_team_id',
        'answered_by_user_id',
        'selected_option',
        'is_correct',
        'time_taken_seconds',
        'points_awarded',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(GameQuestion::class, 'game_question_id');
    }

    public function team()
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'answered_by_user_id');
    }
}
