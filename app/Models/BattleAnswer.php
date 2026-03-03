<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'battle_round_id',
        'battle_participant_id',
        'selected_option',
        'is_correct',
        'points_awarded',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'answered_at' => 'datetime',
        ];
    }

    public function round()
    {
        return $this->belongsTo(BattleRound::class, 'battle_round_id');
    }

    public function participant()
    {
        return $this->belongsTo(BattleParticipant::class, 'battle_participant_id');
    }
}
