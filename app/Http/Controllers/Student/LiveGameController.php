<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\GameTeam;
use App\Models\GameParticipant;
use App\Models\GameQuestion;
use App\Models\GameAnswer;
use App\Models\GameChat;
use Illuminate\Http\Request;

class LiveGameController extends Controller
{
    /**
     * List games available to the student
     */
    public function index()
    {
        $userId = auth()->id();

        // Find games the student is a participant in
        $participantGameIds = GameParticipant::where('user_id', $userId)
            ->with('team')
            ->get()
            ->pluck('team.game_session_id')
            ->unique();

        $games = GameSession::whereIn('id', $participantGameIds)
            ->with('course')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('student.games.index', compact('games'));
    }

    /**
     * Join the game room
     */
    public function room(GameSession $game)
    {
        $userId = auth()->id();

        // Find the user's team
        $participant = GameParticipant::whereHas('team', function ($q) use ($game) {
            $q->where('game_session_id', $game->id);
        })->where('user_id', $userId)->first();

        if (!$participant) {
            return redirect()->route('student.games.index')
                ->with('error', 'أنت مش مشترك في اللعبة دي.');
        }

        $team = $participant->team;
        $game->load(['teams.participants.user', 'questions']);

        // Get current question if game is active
        $currentQuestion = null;
        $timeRemaining = 0;
        if ($game->status === 'active' && $game->current_question_index >= 0) {
            $currentQuestion = $game->questions->skip($game->current_question_index)->first();
            if ($currentQuestion && $game->current_question_start_time) {
                $elapsed = now()->diffInSeconds($game->current_question_start_time);
                $timeRemaining = max(0, $currentQuestion->time_limit_seconds - $elapsed);
            }
        }

        // Check if team already answered current question
        $teamAnswered = false;
        $teamAnswer = null;
        if ($currentQuestion) {
            $teamAnswer = GameAnswer::where('game_question_id', $currentQuestion->id)
                ->where('game_team_id', $team->id)
                ->first();
            $teamAnswered = $teamAnswer !== null;
        }

        // Get team chat messages
        $chats = $team->chats()->with('user')->latest()->take(50)->get()->reverse()->values();

        // Leaderboard
        $leaderboard = $game->teams()->orderByDesc('score')->get();

        return view('student.games.room', compact(
            'game', 'team', 'participant', 'currentQuestion',
            'timeRemaining', 'teamAnswered', 'teamAnswer',
            'chats', 'leaderboard'
        ));
    }

    /**
     * Submit an answer (AJAX - only captains can)
     */
    public function submitAnswer(Request $request, GameSession $game)
    {
        $request->validate([
            'question_id' => 'required|exists:game_questions,id',
            'selected_option' => 'required|string',
        ]);

        $userId = auth()->id();

        // Find participant
        $participant = GameParticipant::whereHas('team', function ($q) use ($game) {
            $q->where('game_session_id', $game->id);
        })->where('user_id', $userId)->first();

        if (!$participant) {
            return response()->json(['error' => 'You are not a participant.'], 403);
        }

        if (!$participant->is_captain) {
            return response()->json(['error' => 'Only the captain can submit answers!'], 403);
        }

        // Check game is active
        if ($game->status !== 'active') {
            return response()->json(['error' => 'Game is not active.'], 400);
        }

        // Check if team already answered
        $existing = GameAnswer::where('game_question_id', $request->question_id)
            ->where('game_team_id', $participant->game_team_id)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Your team already answered this question.'], 400);
        }

        // Find the question
        $question = GameQuestion::findOrFail($request->question_id);
        $isCorrect = $request->selected_option === $question->correct_answer;

        // Calculate time taken
        $timeTaken = 0;
        if ($game->current_question_start_time) {
            $timeTaken = now()->diffInSeconds($game->current_question_start_time);
        }

        // Points: correct answers get points, with speed bonus
        $pointsAwarded = 0;
        if ($isCorrect) {
            // Base points + speed bonus (faster = more bonus)
            $speedBonus = max(0, $question->time_limit_seconds - $timeTaken);
            $pointsAwarded = $question->points + $speedBonus;

            // Update team score
            $participant->team->increment('score', $pointsAwarded);
        }

        // Save answer
        $answer = GameAnswer::create([
            'game_question_id' => $question->id,
            'game_team_id' => $participant->game_team_id,
            'answered_by_user_id' => $userId,
            'selected_option' => $request->selected_option,
            'is_correct' => $isCorrect,
            'time_taken_seconds' => $timeTaken,
            'points_awarded' => $pointsAwarded,
        ]);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'points_awarded' => $pointsAwarded,
            'team_score' => $participant->team->fresh()->score,
        ]);
    }

    /**
     * Send a chat message (AJAX)
     */
    public function sendChat(Request $request, GameSession $game)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userId = auth()->id();

        $participant = GameParticipant::whereHas('team', function ($q) use ($game) {
            $q->where('game_session_id', $game->id);
        })->where('user_id', $userId)->first();

        if (!$participant) {
            return response()->json(['error' => 'Not a participant.'], 403);
        }

        $chat = GameChat::create([
            'game_team_id' => $participant->game_team_id,
            'user_id' => $userId,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'user_name' => auth()->user()->name,
                'message' => $chat->message,
                'created_at' => $chat->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Get latest game state (AJAX polling)
     */
    public function poll(GameSession $game)
    {
        $userId = auth()->id();

        $participant = GameParticipant::whereHas('team', function ($q) use ($game) {
            $q->where('game_session_id', $game->id);
        })->where('user_id', $userId)->first();

        if (!$participant) {
            return response()->json(['error' => 'Not a participant.'], 403);
        }

        $game->refresh();
        $team = $participant->team->fresh();

        // Current question
        $currentQuestion = null;
        $timeRemaining = 0;
        if ($game->status === 'active' && $game->current_question_index >= 0) {
            $q = $game->questions()->skip($game->current_question_index)->first();
            if ($q) {
                $currentQuestion = [
                    'id' => $q->id,
                    'text' => $q->question_text,
                    'options' => $q->options,
                    'time_limit' => $q->time_limit_seconds,
                    'points' => $q->points,
                    'index' => $game->current_question_index,
                    'total' => $game->questions()->count(),
                ];

                if ($game->current_question_start_time) {
                    $elapsed = now()->diffInSeconds($game->current_question_start_time);
                    $timeRemaining = max(0, $q->time_limit_seconds - $elapsed);
                }

                // Check if team answered
                $teamAnswer = GameAnswer::where('game_question_id', $q->id)
                    ->where('game_team_id', $team->id)
                    ->first();

                $currentQuestion['team_answered'] = $teamAnswer !== null;
                $currentQuestion['team_answer'] = $teamAnswer ? [
                    'selected' => $teamAnswer->selected_option,
                    'is_correct' => $teamAnswer->is_correct,
                    'points' => $teamAnswer->points_awarded,
                ] : null;
            }
        }

        // Captain info
        $captain = $team->captain;

        // Latest chats
        $latestChats = GameChat::where('game_team_id', $team->id)
            ->with('user:id,name')
            ->latest()
            ->take(20)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($c) => [
                'id' => $c->id,
                'user_name' => $c->user->name,
                'message' => $c->message,
                'created_at' => $c->created_at->diffForHumans(),
                'is_mine' => $c->user_id === $userId,
            ]);

        // Leaderboard
        $leaderboard = $game->teams()->orderByDesc('score')->get()->map(fn($t) => [
            'name' => $t->name,
            'color' => $t->color_hex,
            'score' => $t->score,
            'is_mine' => $t->id === $team->id,
        ]);

        return response()->json([
            'status' => $game->status,
            'question' => $currentQuestion,
            'time_remaining' => $timeRemaining,
            'is_captain' => $participant->fresh()->is_captain,
            'captain_name' => $captain ? $captain->user->name : null,
            'team_score' => $team->score,
            'chats' => $latestChats,
            'leaderboard' => $leaderboard,
        ]);
    }
}
