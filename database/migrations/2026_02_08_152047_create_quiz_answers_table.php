<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->string('user_answer', 1)->comment('A, B, C, or D');
            $table->boolean('is_correct');
            $table->integer('time_taken')->nullable()->comment('in seconds');
            $table->boolean('audio_played')->default(false);
            $table->integer('audio_replay_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('quiz_attempt_id');
            $table->index('question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
    }
};