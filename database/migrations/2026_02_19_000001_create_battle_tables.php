<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
            $table->integer('max_players')->default(10);
            $table->integer('lobby_timer_seconds')->default(120);
            $table->integer('question_timer_seconds')->default(30);
            $table->integer('question_count')->nullable();
            $table->integer('current_question_index')->default(0);
            $table->timestamp('current_question_started_at')->nullable();
            $table->string('team_a_name')->default('Red Team');
            $table->integer('team_a_score')->default(0);
            $table->string('team_b_name')->default('Blue Team');
            $table->integer('team_b_score')->default(0);
            $table->string('winner_team')->nullable(); // 'a', 'b', or 'draw'
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('battle_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('team', ['a', 'b'])->nullable();
            $table->integer('individual_score')->default(0);
            $table->timestamps();

            $table->unique(['battle_room_id', 'user_id']);
        });

        Schema::create('battle_rounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_room_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('round_number');
            $table->integer('points')->default(10);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('battle_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_round_id')->constrained()->onDelete('cascade');
            $table->foreignId('battle_participant_id')->constrained()->onDelete('cascade');
            $table->string('selected_option', 1); // A, B, C, D
            $table->boolean('is_correct')->default(false);
            $table->integer('points_awarded')->default(0);
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['battle_round_id', 'battle_participant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_answers');
        Schema::dropIfExists('battle_rounds');
        Schema::dropIfExists('battle_participants');
        Schema::dropIfExists('battle_rooms');
    }
};
