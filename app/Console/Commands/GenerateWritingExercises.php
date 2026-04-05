<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use App\Models\WritingExercise;
use App\Services\WritingExerciseContentFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateWritingExercises extends Command
{
    protected $signature = 'writing:generate-exercises
        {course_id : The course id}
        {--overwrite : Update existing writing exercises}
        {--limit= : Limit number of lessons processed}
        {--dry-run : Print what would be created without writing}
        {--no-model-answer : Leave model_answer empty}';

    protected $description = 'Generate a writing exercise for each lesson in a course.';

    public function handle(WritingExerciseContentFactory $factory): int
    {
        $courseId = (int) $this->argument('course_id');
        $overwrite = (bool) $this->option('overwrite');
        $dryRun = (bool) $this->option('dry-run');
        $noModelAnswer = (bool) $this->option('no-model-answer');
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;

        $query = Lesson::query()
            ->where('course_id', $courseId)
            ->orderBy('order_index')
            ->orderBy('id');

        if ($limit && $limit > 0) {
            $query->limit($limit);
        }

        $lessons = $query->get();

        if ($lessons->isEmpty()) {
            $this->error("No lessons found for course_id={$courseId}");
            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($lessons as $lesson) {
            $lesson->loadMissing('writingExercise');

            $payload = $factory->buildForLesson($lesson);

            if ($noModelAnswer) {
                $payload['model_answer'] = null;
            }

            $exists = (bool) $lesson->writingExercise;

            if ($exists && !$overwrite) {
                $skipped++;
                $this->line("SKIP  lesson_id={$lesson->id} writing_exercise_id={$lesson->writingExercise->id} title=\"{$lesson->title}\"");
                continue;
            }

            if ($dryRun) {
                $action = $exists ? 'UPDATE' : 'CREATE';
                $this->line("DRY   {$action} lesson_id={$lesson->id} title=\"{$lesson->title}\" writing_title=\"{$payload['title']}\"");
                continue;
            }

            DB::transaction(function () use ($lesson, $payload, $exists, &$created, &$updated) {
                if (!$lesson->has_writing_exercise) {
                    $lesson->has_writing_exercise = true;
                    $lesson->save();
                }

                if ($exists) {
                    $lesson->writingExercise->update($payload);
                    $updated++;
                    return;
                }

                WritingExercise::create(array_merge($payload, [
                    'lesson_id' => $lesson->id,
                ]));
                $created++;
            });

            $this->line(($exists ? 'UPD' : 'NEW') . "   lesson_id={$lesson->id} title=\"{$lesson->title}\"");
        }

        $this->info("Done. created={$created} updated={$updated} skipped={$skipped}");

        return self::SUCCESS;
    }
}

