<?php

namespace Tests\Feature;

use App\Models\BattleParticipant;
use App\Models\BattleRoom;
use App\Models\BattleRound;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\User;
use App\Services\DeviceAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BattlePagesRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_battle_pages_render_successfully(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $question = $this->createQuestion($course);
        $deviceToken = str_repeat('7', 40);

        $this->enroll($student, $course);

        $waitingRoom = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'waiting',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->addMinute(),
            'question_timer_seconds' => 30,
        ]);

        BattleParticipant::create([
            'battle_room_id' => $waitingRoom->id,
            'user_id' => $student->id,
        ]);

        $this->actingAs($student)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, $deviceToken)
            ->get(route('student.battle.index'))
            ->assertOk();

        $this->actingAs($student)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, $deviceToken)
            ->get(route('student.battle.lobby', $waitingRoom))
            ->assertOk();

        $playingRoom = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'playing',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->subMinute(),
            'question_timer_seconds' => 30,
            'question_count' => 1,
            'current_question_index' => 0,
            'current_question_started_at' => now(),
            'started_at' => now(),
        ]);

        $playingParticipant = BattleParticipant::create([
            'battle_room_id' => $playingRoom->id,
            'user_id' => $student->id,
            'team' => 'a',
        ]);

        BattleRound::create([
            'battle_room_id' => $playingRoom->id,
            'question_id' => $question->id,
            'round_number' => 1,
            'points' => 10,
            'started_at' => now(),
        ]);

        $this->actingAs($student)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, $deviceToken)
            ->get(route('student.battle.play', $playingRoom))
            ->assertOk()
            ->assertSee(route('student.battle.leave', $playingRoom), false);

        $finishedRoom = BattleRoom::create([
            'course_id' => $course->id,
            'status' => 'finished',
            'max_players' => 4,
            'lobby_timer_seconds' => 120,
            'lobby_ends_at' => now()->subMinutes(2),
            'question_timer_seconds' => 30,
            'question_count' => 1,
            'current_question_index' => 0,
            'current_question_started_at' => now()->subMinute(),
            'team_a_score' => 10,
            'team_b_score' => 5,
            'winner_team' => 'a',
            'started_at' => now()->subMinutes(2),
            'finished_at' => now()->subMinute(),
        ]);

        BattleParticipant::create([
            'battle_room_id' => $finishedRoom->id,
            'user_id' => $student->id,
            'team' => 'a',
            'individual_score' => 10,
        ]);

        BattleRound::create([
            'battle_room_id' => $finishedRoom->id,
            'question_id' => $question->id,
            'round_number' => 1,
            'points' => 10,
            'started_at' => now()->subMinutes(2),
            'finished_at' => now()->subMinute(),
        ]);

        $this->actingAs($student)
            ->withUnencryptedCookie(DeviceAccessService::COOKIE_NAME, $deviceToken)
            ->get(route('student.battle.results', $finishedRoom))
            ->assertOk();
    }

    public function test_admin_battle_settings_page_renders_successfully(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.settings.battle'))
            ->assertOk();
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

    private function createQuestion(Course $course): Question
    {
        return Question::create([
            'course_id' => $course->id,
            'question_text' => 'Sample battle question',
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
