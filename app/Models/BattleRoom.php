<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'status',
        'max_players',
        'lobby_timer_seconds',
        'lobby_ends_at',
        'question_timer_seconds',
        'question_count',
        'current_question_index',
        'current_question_started_at',
        'team_a_name',
        'team_a_score',
        'team_b_name',
        'team_b_score',
        'winner_team',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'lobby_ends_at' => 'datetime',
            'current_question_started_at' => 'datetime',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function participants()
    {
        return $this->hasMany(BattleParticipant::class);
    }

    public function rounds()
    {
        return $this->hasMany(BattleRound::class)->orderBy('round_number');
    }

    public function answers()
    {
        return $this->hasManyThrough(BattleAnswer::class, BattleRound::class);
    }

    // ==================== TEAM HELPERS ====================

    public function teamA()
    {
        return $this->participants()->where('team', 'a');
    }

    public function teamB()
    {
        return $this->participants()->where('team', 'b');
    }

    public function currentRound()
    {
        return $this->rounds()->where('round_number', $this->current_question_index + 1)->first();
    }

    // ==================== SCOPES ====================

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    // ==================== METHODS ====================

    public function playerCount()
    {
        return $this->participants()->count();
    }

    public function isFull()
    {
        return $this->playerCount() >= $this->max_players;
    }

    public function hasPlayer($userId)
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    public function lobbyTimeRemaining()
    {
        if ($this->lobby_ends_at) {
            return max(0, $this->lobby_ends_at->getTimestamp() - now()->getTimestamp());
        }

        // Fallback for old rooms
        if (!$this->created_at) return $this->lobby_timer_seconds;
        $elapsed = now()->diffInSeconds($this->created_at);
        return max(0, $this->lobby_timer_seconds - $elapsed);
    }

    public function isLobbyExpired()
    {
        return $this->lobbyTimeRemaining() <= 0;
    }
}
