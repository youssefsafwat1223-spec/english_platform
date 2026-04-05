<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writing_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('writing_exercise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->text('answer_text');
            $table->unsignedSmallInteger('word_count')->default(0);
            $table->string('status')->default('evaluated');
            $table->unsignedTinyInteger('overall_score')->nullable();
            $table->unsignedTinyInteger('grammar_score')->nullable();
            $table->unsignedTinyInteger('vocabulary_score')->nullable();
            $table->unsignedTinyInteger('coherence_score')->nullable();
            $table->unsignedTinyInteger('task_score')->nullable();
            $table->json('grammar_feedback_json')->nullable();
            $table->json('ai_feedback_json')->nullable();
            $table->text('rewrite_text')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['writing_exercise_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writing_submissions');
    }
};
