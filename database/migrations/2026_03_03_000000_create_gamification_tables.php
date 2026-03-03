<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. The Game Session (The Arena Event)
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            
            // Requirements
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('min_lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
            
            // Scheduling & Status
            $table->timestamp('start_time');
            $table->enum('status', ['scheduled', 'active', 'completed', 'cancelled'])->default('scheduled');
            
            // Game State (for real-time tracking)
            $table->integer('current_question_index')->default(-1); // -1 = Not started, 0 = Q1, etc.
            $table->timestamp('current_question_start_time')->nullable();
            
            $table->timestamps();
        });

        // 2. Teams within a Session
        Schema::create('game_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Red Dragons", "Blue Eagles"
            $table->string('color_hex')->default('#3b82f6'); // Team color
            $table->integer('score')->default(0);
            $table->timestamps();
        });

        // 3. Participants (Students assigned to teams)
        Schema::create('game_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Role & Status
            $table->boolean('is_captain')->default(false); // Only captain can answer
            $table->boolean('is_online')->default(false);
            
            $table->integer('individual_score')->default(0); // For personal achievements
            
            $table->timestamps();
            
            // Ensure user is only in one team per game session (handled by logic, but index helps)
            $table->unique(['game_team_id', 'user_id']); 
        });

        // 4. Questions for the Session
        Schema::create('game_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained()->cascadeOnDelete();
            
            $table->text('question_text');
            $table->string('image_url')->nullable();
            $table->integer('time_limit_seconds')->default(30);
            $table->integer('points')->default(100);
            
            $table->json('options'); // ["A", "B", "C", "D"]
            $table->string('correct_answer'); // e.g., "A"
            
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 5. Answers (Tracking who answered what)
        Schema::create('game_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answered_by_user_id')->constrained('users')->cascadeOnDelete(); // The captain who clicked
            
            $table->string('selected_option');
            $table->boolean('is_correct');
            $table->integer('time_taken_seconds'); // For tie-breaking or speed bonus
            $table->integer('points_awarded')->default(0);
            
            $table->timestamps();
            
            $table->unique(['game_question_id', 'game_team_id']); // One answer per team per question
        });

        // 6. Team Chat (Simple text messages)
        Schema::create('game_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_chats');
        Schema::dropIfExists('game_answers');
        Schema::dropIfExists('game_questions');
        Schema::dropIfExists('game_participants');
        Schema::dropIfExists('game_teams');
        Schema::dropIfExists('game_sessions');
    }
};
