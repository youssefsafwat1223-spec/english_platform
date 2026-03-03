<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('set null');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'fill_blank']);
            $table->string('option_a', 500)->nullable();
            $table->string('option_b', 500)->nullable();
            $table->string('option_c', 500)->nullable();
            $table->string('option_d', 500)->nullable();
            $table->string('correct_answer', 1)->comment('A, B, C, or D');
            $table->text('explanation')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('points')->default(5);
            $table->boolean('has_audio')->default(false);
            $table->string('audio_path')->nullable()->comment('TTS or custom audio file');
            $table->integer('audio_duration')->nullable()->comment('in seconds');
            $table->json('tts_settings')->nullable()->comment('voice, speed, language settings');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('course_id');
            $table->index('lesson_id');
            $table->index('question_type');
            $table->index('difficulty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};