<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Str;

class GenerateMissingSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slugs:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing slugs for existing courses and lessons';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to generate missing slugs...');

        // 1. Generate slugs for Courses
        $courses = Course::all();
        $courseCount = 0;
        foreach ($courses as $course) {
            // Check if slug is empty, numeric (maybe fallback), or just needs recreation
            if (empty($course->slug) || is_numeric($course->slug)) {
                // For arabic support using regex if Str::slug strips chars
                $slug = Str::slug($course->title);
                if (empty($slug)) {
                    // Fallback for full arabic support
                    $slug = preg_replace('/\s+/u', '-', trim($course->title));
                }
                
                // Ensure uniqueness
                $originalSlug = $slug;
                $count = 1;
                while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $course->slug = $slug;
                $course->saveQuietly(); // save without triggering model events
                $courseCount++;
            }
        }
        $this->line("Updated {$courseCount} courses.");

        // 2. Generate slugs for Lessons
        $lessons = Lesson::all();
        $lessonCount = 0;
        foreach ($lessons as $lesson) {
            if (empty($lesson->slug) || is_numeric($lesson->slug)) {
                $slug = Str::slug($lesson->title);
                if (empty($slug)) {
                    $slug = preg_replace('/\s+/u', '-', trim($lesson->title));
                }
                
                // Ensure uniqueness scoped to course (or global, better global for simple URL structure)
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
