<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateMissingSlugs extends Command
{
    protected $signature = 'slugs:generate';
    protected $description = 'Generate missing slugs for existing courses and lessons';

    public function handle()
    {
        $this->info('Starting to generate missing slugs...');

        $courses = Course::all();
        $courseCount = 0;
        foreach ($courses as $course) {
            if (empty($course->slug) || is_numeric($course->slug)) {
                $slug = Str::slug($course->title);
                if (empty($slug)) {
                    $slug = preg_replace('/\s+/u', '-', trim($course->title));
                }

                $originalSlug = $slug;
                $count = 1;
                while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $course->slug = $slug;
                $course->saveQuietly();
                $courseCount++;
            }
        }
        $this->line("Updated {$courseCount} courses.");

        $lessons = Lesson::all();
        $lessonCount = 0;
        foreach ($lessons as $lesson) {
            if (empty($lesson->slug) || is_numeric($lesson->slug)) {
                $slug = Str::slug($lesson->title);
                if (empty($slug)) {
                    $slug = preg_replace('/\s+/u', '-', trim($lesson->title));
                }

                $originalSlug = $slug;
                $count = 1;
                while (Lesson::where('slug', $slug)->where('id', '!=', $lesson->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $lesson->slug = $slug;
                $lesson->saveQuietly();
                $lessonCount++;
            }
        }
        $this->line("Updated {$lessonCount} lessons.");

        $this->info('All missing slugs have been generated successfully!');
    }
}

