<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('writing_exercises', function (Blueprint $table) {
            $table->foreignId('course_level_id')->nullable()->after('lesson_id')
                ->constrained('course_levels')->nullOnDelete();
        });

        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->foreignId('course_level_id')->nullable()->after('lesson_id')
                ->constrained('course_levels')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('writing_exercises', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\CourseLevel::class);
            $table->dropColumn('course_level_id');
        });

        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\CourseLevel::class);
            $table->dropColumn('course_level_id');
        });
    }
};
