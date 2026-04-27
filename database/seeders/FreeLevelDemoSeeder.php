<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseLevel;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FreeLevelDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        $course = Course::updateOrCreate(
            ['slug' => 'free-level-demo'],
            [
                'title'                    => 'Demo: كورس فيه عنوان مجاني',
                'short_description'        => 'كورس تجريبي لاختبار ميزة العنوان المجاني — فيه عنوان مجاني للتجربة وعناوين مدفوعة.',
                'description'              => "كورس تجريبي لشرح ميزة العناوين المجانية.\n\nيحتوي على:\n• عنوان مجاني للتجربة (Free Preview)\n• عنوان مدفوع كامل (Beginner)\n• عنوان مدفوع متقدم (Intermediate)\n\nالطلاب غير المشتركين يقدروا يفتحوا دروس العنوان المجاني فقط.",
                'price'                    => 99.00,
                'estimated_duration_weeks' => 4,
                'is_active'                => true,
                'order_index'              => 999,
                'is_exam'                  => false,
                'created_by'               => $admin?->id,
            ]
        );

        $this->command?->info("Course ready: {$course->title} (id: {$course->id})");

        $levels = [
            [
                'title'       => '🎁 عنوان مجاني للتجربة',
                'description' => 'دروس قصيرة تجريبية — متاحة لأي طالب بدون اشتراك. جرّب وقرر.',
                'order_index' => 1,
                'is_free'     => true,
                'lessons' => [
                    ['title' => 'Free Lesson 1: Welcome / مرحباً بكم', 'duration' => 180, 'desc' => 'درس ترحيبي قصير يشرح هدف الكورس.'],
                    ['title' => 'Free Lesson 2: How English Sounds Work', 'duration' => 240, 'desc' => 'مقدمة سريعة عن أصوات الإنجليزية.'],
                    ['title' => 'Free Lesson 3: Greetings / التحية', 'duration' => 200, 'desc' => 'تحيات يومية وممارستها.'],
                ],
            ],
            [
                'title'       => 'Beginner — Foundations',
                'description' => 'الأساسيات الكاملة للمبتدئين.',
                'order_index' => 2,
                'is_free'     => false,
                'lessons' => [
                    ['title' => 'Lesson 1: The Alphabet', 'duration' => 360, 'desc' => 'الحروف الإنجليزية ونطقها.'],
                    ['title' => 'Lesson 2: Numbers 1-100', 'duration' => 420, 'desc' => 'الأرقام من 1 لـ 100.'],
                    ['title' => 'Lesson 3: Days & Months', 'duration' => 300, 'desc' => 'أيام الأسبوع والأشهر.'],
                    ['title' => 'Lesson 4: Basic Verbs', 'duration' => 480, 'desc' => 'أهم الأفعال للمبتدئين.'],
                ],
            ],
            [
                'title'       => 'Intermediate — Conversations',
                'description' => 'محادثات ومواقف يومية.',
                'order_index' => 3,
                'is_free'     => false,
                'lessons' => [
                    ['title' => 'Lesson 5: At the Restaurant', 'duration' => 540, 'desc' => 'في المطعم — كلمات وعبارات.'],
                    ['title' => 'Lesson 6: At the Airport', 'duration' => 600, 'desc' => 'في المطار — أهم العبارات.'],
                    ['title' => 'Lesson 7: Job Interview', 'duration' => 720, 'desc' => 'مقابلة العمل.'],
                ],
            ],
        ];

        foreach ($levels as $levelData) {
            $lessons = $levelData['lessons'];
            unset($levelData['lessons']);

            $level = CourseLevel::updateOrCreate(
                [
                    'course_id'   => $course->id,
                    'order_index' => $levelData['order_index'],
                ],
                array_merge($levelData, [
                    'course_id' => $course->id,
                    'slug'      => Str::slug($levelData['title']),
                    'is_active' => true,
                ])
            );

            $this->command?->info("  Level: {$level->title} " . ($level->is_free ? '🆓' : '🔒'));

            foreach ($lessons as $i => $lessonData) {
                Lesson::updateOrCreate(
                    [
                        'course_id'       => $course->id,
                        'course_level_id' => $level->id,
                        'order_index'     => $i + 1,
                    ],
                    [
                        'title'          => $lessonData['title'],
                        'slug'           => Str::slug($lessonData['title']) . '-' . $level->id . '-' . ($i + 1),
                        'description'    => $lessonData['desc'],
                        'video_duration' => $lessonData['duration'] ?? null,
                        'text_content'   => "محتوى الدرس: {$lessonData['title']}\n\nهذا درس تجريبي لاختبار ميزة العناوين المجانية.",
                    ]
                );
            }
        }

        $this->command?->info('');
        $this->command?->info("✓ Demo course ready at: /student/courses/{$course->slug}");
        $this->command?->warn('Login as a non-enrolled student to see the free preview section.');
    }
}
