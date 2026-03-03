<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'battle_room_id',
        'question_id',
        'round_number',
        'points',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function room()
    {
        return $this->belongsTo(BattleRoom::class, 'battle_room_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answers()
    {
        return $this->hasMany(BattleAnswer::class);
    }

    public function isAllAnswered()
    {
        $totalParticipants = $this->room->participants()->whereNotNull('team')->count();
        $totalAnswers = $this->answers()->count();
        return $totalAnswers >= $totalParticipants;
    }

    public function timeRemaining()
    {
        if (!$this->started_at) return (int) $this->room->question_timer_seconds;
        $elapsed = abs(now()->diffInSeconds($this->started_at));
        return (int) max(0, $this->room->question_timer_seconds - $elapsed);
    }

    public function isTimerExpired()
    {
        return $this->timeRemaining() <= 0;
    }
}
