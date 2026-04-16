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
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });

        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });

        Schema::table('writing_submissions', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });

        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('writing_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable(false)->change();
        });

        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable(false)->change();
        });

        Schema::table('writing_submissions', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable(false)->change();
        });

        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable(false)->change();
        });
    }
};
