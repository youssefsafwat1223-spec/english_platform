<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_team_id',
        'user_id',
        'is_captain',
        'is_online',
        'individual_score',
    ];

    protected $casts = [
        'is_captain' => 'boolean',
        'is_online' => 'boolean',
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
