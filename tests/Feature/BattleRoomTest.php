<?php

namespace Tests\Feature;

use App\Models\BattleParticipant;
use App\Models\BattleRoom;
use App\Models\BattleRound;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\SystemSetting;
use App\Models\User;
use App\Services\BattleRoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BattleRoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_enrolled_user_cannot_auto_join_waiting_room_from_lobby_link(): void
    {
        $course = Course::factory()->create();
        $creator = User::factory()->create();
        $outsider = User::factory()->create();

        $this->enroll($creator, $course);

        $room = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'waiting',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->addMinute(),
            'question_timer_seconds' => 30,
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $creator->id,
        ]);

        $response = $this->actingAs($outsider)->get(route('student.battle.lobby', $room));

        $response->assertRedirect(route('student.battle.index'));
        $this->assertDatabaseMissing('battle_participants', [
            'battle_room_id' => $room->id,
            'user_id' => $outsider->id,
        ]);
    }

    public function test_join_respects_configured_minimum_question_count(): void
    {
        SystemSetting::set('battle_min_questions', 3, 'integer', 'battle');

        $user = User::factory()->create();
        $course = Course::factory()->create();

        $this->enroll($user, $course);
        $this->createQuestion($course, 1);
        $this->createQuestion($course, 2);

        $response = $this->actingAs($user)->post(route('student.battle.join', $course));

        $response->assertRedirect();
        $this->assertDatabaseCount('battle_rooms', 0);
    }

    public function test_answer_rejects_future_rounds_that_have_not_started(): void
    {
        $course = Course::factory()->create();
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();

        $this->enroll($playerOne, $course);
        $this->enroll($playerTwo, $course);

        $questionOne = $this->createQuestion($course, 1);
        $questionTwo = $this->createQuestion($course, 2);

        $room = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'playing',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->subMinute(),
            'question_timer_seconds' => 30,
            'question_count' => 2,
            'current_question_index' => 0,
            'current_question_started_at' => now(),
            'started_at' => now(),
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $playerOne->id,
            'team' => 'a',
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $playerTwo->id,
            'team' => 'b',
        ]);

        BattleRound::create([
            'battle_room_id' => $room->id,
            'question_id' => $questionOne->id,
            'round_number' => 1,
            'points' => 10,
            'started_at' => now(),
        ]);

        $futureRound = BattleRound::create([
            'battle_room_id' => $room->id,
            'question_id' => $questionTwo->id,
            'round_number' => 2,
            'points' => 10,
        ]);

        $response = $this->actingAs($playerOne)->postJson(route('student.battle.answer', $room), [
            'round_id' => $futureRound->id,
            'selected_option' => 'A',
        ]);

        $response->assertStatus(400)->assertJson([
            'error' => 'Invalid or inactive round',
        ]);
    }

    public function test_sync_room_state_awards_points_only_once_when_room_finishes(): void
    {
        $course = Course::factory()->create();
        $playerOne = User::factory()->create(['total_points' => 0]);
        $playerTwo = User::factory()->create(['total_points' => 0]);

        $question = $this->createQuestion($course, 1);

        $room = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'playing',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->subMinute(),
            'question_timer_seconds' => 30,
            'question_count' => 1,
            'current_question_index' => 0,
            'current_question_started_at' => now()->subSeconds(45),
            'team_a_score' => 10,
            'team_b_score' => 5,
            'started_at' => now()->subMinute(),
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $playerOne->id,
            'team' => 'a',
            'individual_score' => 10,
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $playerTwo->id,
            'team' => 'b',
            'individual_score' => 5,
        ]);

        BattleRound::create([
            'battle_room_id' => $room->id,
            'question_id' => $question->id,
            'round_number' => 1,
            'points' => 10,
            'started_at' => now()->subSeconds(45),
        ]);

        $service = app(BattleRoomService::class);
        $service->syncRoomState($room);
        $service->syncRoomState($room->fresh());

        $room->refresh();
        $playerOne->refresh();
        $playerTwo->refresh();

        $this->assertSame('finished', $room->status);
        $this->assertSame('a', $room->winner_team);
        $this->assertSame(10, $playerOne->total_points);
        $this->assertSame(5, $playerTwo->total_points);
    }

    public function test_cleanup_command_closes_abandoned_playing_rooms(): void
    {
        SystemSetting::set('battle_inactivity_timeout', 60, 'integer', 'battle');

        $course = Course::factory()->create();
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();
        $question = $this->createQuestion($course, 1);

        $room = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'playing',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->subMinute(),
            'question_timer_seconds' => 30,
            'question_count' => 1,
            'current_question_index' => 0,
            'current_question_started_at' => now()->subMinutes(5),
            'started_at' => now()->subMinutes(6),
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $playerOne->id,
            'team' => 'a',
        ]);

        BattleParticipant::create([
            'battle_room_id' => $room->id,
            'user_id' => $playerTwo->id,
            'team' => 'b',
        ]);

        BattleRound::create([
            'battle_room_id' => $room->id,
            'question_id' => $question->id,
            'round_number' => 1,
            'points' => 10,
            'started_at' => now()->subMinutes(5),
        ]);

        Artisan::call('battle:cleanup-stale-rooms');

        $room->refresh();

        $this->assertSame('finished', $room->status);
        $this->assertNull($room->winner_team);
        $this->assertNotNull($room->finished_at);
    }

    private function enroll(User $user, Course $course): void
    {
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => $course->price,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 0,
        ]);
    }

    private function createQuestion(Course $course, int $index): Question
    {
        return Question::create([
            'course_id' => $course->id,
            'question_text' => 'Question ' . $index,
            'question_type' => 'multiple_choice',
            'option_a' => 'Option A',
            'option_b' => 'Option B',
            'option_c' => 'Option C',
            'option_d' => 'Option D',
            'correct_answer' => 'A',
            'difficulty' => 'medium',
            'points' => 10,
        ]);
    }
}
