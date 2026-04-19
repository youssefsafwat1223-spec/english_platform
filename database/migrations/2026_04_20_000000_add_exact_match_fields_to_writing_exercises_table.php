<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('writing_exercises', function (Blueprint $table) {
            $table->string('evaluation_type')->default('ai')->after('passing_score');
            $table->json('questions_json')->nullable()->after('evaluation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('writing_exercises', function (Blueprint $table) {
            $table->dropColumn(['evaluation_type', 'questions_json']);
        });
    }
};
