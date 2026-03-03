<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_for');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->string('user_answer', 1)->nullable()->comment('A, B, C, or D');
            $table->boolean('is_correct')->nullable();
            $table->integer('points_earned')->default(0);
            $table->string('telegram_message_id')->nullable();
            $table->timestamps();

            // Indexes and constraints
            $table->unique(['user_id', 'scheduled_for']);
            $table->index('user_id');
            $table->index('question_id');
            $table->index('scheduled_for');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_questions');
    }
};