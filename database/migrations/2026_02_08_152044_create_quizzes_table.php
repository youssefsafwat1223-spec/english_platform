<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('quiz_type', ['lesson', 'final_exam']);
            $table->text('description')->nullable();
            $table->integer('total_questions');
            $table->integer('duration_minutes');
            $table->integer('passing_score')->default(70)->comment('percentage');
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_retake')->default(true);
            $table->boolean('show_results_immediately')->default(true);
            $table->boolean('enable_audio')->default(true)->comment('enable TTS for questions');
            $table->boolean('audio_auto_play')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('course_id');
            $table->index('lesson_id');
            $table->index('quiz_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};