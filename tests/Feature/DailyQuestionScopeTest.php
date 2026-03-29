<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\DailyQuestion;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Question;
use App\Models\User;
use App\Services\DailyQuestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DailyQuestionScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_questions_only_include_levels_up_to_highest_engaged_level(): void
    {
        [$user, $course, $enrollment, $questions] = $this->buildCourseWithLevels();

        LessonProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $questions['level_2']->lesson_id,
            'enrollment_id' => $enrollment->id,
            'time_spent' => 120,
            'last_position' => 55,
        ]);

        LessonProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $questions['level_1_a']->lesson_id,
            'enrollment_id' => $enrollment->id,
            'time_spent' => 35,
            'last_position' => 10,
        ]);

        $result = app(DailyQuestionService::class)->scheduleQuestionsForEnrollment($user, $enrollment, false);

        $this->assertSame(3, $result['scheduled']);

        $scheduledQuestionIds = DailyQuestion::where('user_id', $user->id)
            ->pluck('question_id')
            ->all();

        $this->assertContains($questions['level_1_a']->id, $scheduledQuestionIds);
        $this->assertContains($questions['level_1_b']->id, $scheduledQuestionIds);
        $this->assertContains($questions['level_2']->id, $scheduledQuestionIds);
        $this->assertNotContains($questions['level_3']->id, $scheduledQuestionIds);
    }

    public function test_daily_question_prompt_mentions_current_level_title(): void
    {
        config(['services.telegram.bot_token' => 'test-token']);

        Http::fake([
            'https://api.telegram.org/*' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 103],
            ], 200),
        ]);

        [$user, $course, $enrollment, $questions, $levels] = $this->buildCourseWithLevels(true);

        LessonProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $questions['level_2']->lesson_id,
            'enrollment_id' => $enrollment->id,
            'time_spent' => 90,
            'last_position' => 25,
        ]);

        app(DailyQuestionService::class)->scheduleQuestionsForEnrollment($user, $enrollment, true);

        Http::assertSent(function ($request) use ($levels) {
            $text = (string) $request['text'];

            return str_contains($text, 'تم تجهيز أسئلة اليوم')
                && str_contains($text, $levels['level_2']->title)
                && str_contains($text, 'عدد الأسئلة: 3');
        });
    }

    private function buildCourseWithLevels(bool $telegramLinked = false): array
    {
        $user = User::factory()->create([
            'telegram_chat_id' => $telegramLinked ? '777001' : null,
            'telegram_linked_at' => $telegramLinked ? now() : null,
        ]);

        $course = Course::factory()->create();

        $levelOne = CourseLevel::create([
            'course_id' => $course->id,
            'title' => 'العنوان الأول',
            'slug' => 'level-one',
            'order_index' => 1,
            'is_active' => true,
        ]);

        $levelTwo = CourseLevel::create([
            'course_id' => $course->id,
            'title' => 'العنوان الثاني',
            'slug' => 'level-two',
            'order_index' => 2,
            'is_active' => true,
        ]);

        $levelThree = CourseLevel::create([
            'course_id' => $course->id,
            'title' => 'العنوان الثالث',
            'slug' => 'level-three',
            'order_index' => 3,
            'is_active' => true,
        ]);

        $lessonOneA = $this->createLesson($course, $levelOne, 1, 'lesson-one-a');
        $lessonOneB = $this->createLesson($course, $levelOne, 2, 'lesson-one-b');
        $lessonTwo = $this->createLesson($course, $levelTwo, 3, 'lesson-two');
        $lessonThree = $this->createLesson($course, $levelThree, 4, 'lesson-three');

        $questionOneA = $this->createQuestion($course, $lessonOneA, 'Question Level 1A');
        $questionOneB = $this->createQuestion($course, $lessonOneB, 'Question Level 1B');
        $questionTwo = $this->createQuestion($course, $lessonTwo, 'Question Level 2');
        $questionThree = $this->createQuestion($course, $lessonThree, 'Question Level 3');

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'price_paid' => $course->price,
            'discount_amount' => 0,
            'progress_percentage' => 0,
            'completed_lessons' => 0,
            'total_lessons' => 4,
        ]);

        return [
            $user,
            $course,
            $enrollment,
            [
                'level_1_a' => $questionOneA,
                'level_1_b' => $questionOneB,
                'level_2' => $questionTwo,
                'level_3' => $questionThree,
            ],
            [
                'level_1' => $levelOne,
                'level_2' => $levelTwo,
                'level_3' => $levelThree,
            ],
        ];
    }

    private function createLesson(Course $course, CourseLevel $level, int $order, string $slug): Lesson
    {
        return Lesson::create([
            'course_id' => $course->id,
            'course_level_id' => $level->id,
            'title' => 'Lesson ' . $order,
            'slug' => $slug,
            'order_index' => $order,
            'is_free' => false,
            'has_quiz' => false,
            'has_pronunciation_exercise' => false,
        ]);
    }

    private function createQuestion(Course $course, Lesson $lesson, string $text): Question
    {
        return Question::create([
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'question_text' => $text,
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
