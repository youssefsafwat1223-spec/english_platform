<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pronunciation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pronunciation_exercise_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->integer('attempt_number')->default(1);
            $table->integer('sentence_number')->comment('1, 2, or 3');
            $table->string('audio_recording_path');
            $table->integer('recording_duration')->comment('in seconds');
            $table->integer('overall_score')->comment('percentage');
            $table->integer('clarity_score')->nullable()->comment('percentage');
            $table->integer('pronunciation_score')->nullable()->comment('percentage');
            $table->integer('fluency_score')->nullable()->comment('percentage');
            $table->text('feedback_text')->nullable();
            $table->string('ai_provider', 50)->nullable()->comment('Google Speech, AWS, etc');
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('pronunciation_exercise_id');
            $table->index('lesson_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pronunciation_attempts');
    }
};