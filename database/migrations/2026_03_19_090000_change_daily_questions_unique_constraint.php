<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change unique constraint from (user_id, scheduled_for) to (user_id, question_id, scheduled_for)
     * to allow multiple questions per user per day.
     */
    public function up(): void
    {
        Schema::table('daily_questions', function (Blueprint $table) {
            // Drop the old unique constraint that only allows 1 question per user per day
            $table->dropUnique('daily_questions_user_id_scheduled_for_unique');

            // Add new unique constraint: same question can't be asked to same user on same day
            $table->unique(['user_id', 'question_id', 'scheduled_for'], 'daily_questions_user_question_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_questions', function (Blueprint $table) {
            $table->dropUnique('daily_questions_user_question_date_unique');
            $table->unique(['user_id', 'scheduled_for'], 'daily_questions_user_id_scheduled_for_unique');
        });
    }
};
