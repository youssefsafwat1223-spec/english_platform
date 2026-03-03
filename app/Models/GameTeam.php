<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_session_id',
        'name',
        'color_hex',
        'score',
    ];

    public function session()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }

    public function participants()
    {
        return $this->hasMany(GameParticipant::class);
    }

    public function captain()
    {
        return $this->hasOne(GameParticipant::class)->where('is_captain', true);
    }

    public function chats()
    {
        return $this->hasMany(GameChat::class);
    }
}
