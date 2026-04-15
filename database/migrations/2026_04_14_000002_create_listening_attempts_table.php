<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listening_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listening_exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('course_level_id')->nullable()->constrained()->cascadeOnDelete();
            $table->json('answers_json');
            $table->json('results_json')->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->unsignedTinyInteger('correct_count')->default(0);
            $table->unsignedTinyInteger('total_questions')->default(0);
            $table->boolean('passed')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['listening_exercise_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listening_attempts');
    }
};
