<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_team_id',
        'user_id',
        'message',
    ];

    public function team()
    {
        return $this->belongsTo(GameTeam::class, 'game_team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
