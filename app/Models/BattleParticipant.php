<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'battle_room_id',
        'user_id',
        'team',
        'individual_score',
    ];

    public function room()
    {
        return $this->belongsTo(BattleRoom::class, 'battle_room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(BattleAnswer::class);
    }
}
