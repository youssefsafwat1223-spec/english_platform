<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // على مستوى الدرس
        Schema::table('lessons', function (Blueprint $table) {
            $table->boolean('has_listening_exercise')->default(false)->after('has_writing_exercise');
        });

        // على مستوى العنوان
        Schema::table('course_levels', function (Blueprint $table) {
            $table->boolean('has_listening_exercise')->default(false)->after('is_active');
            $table->boolean('has_writing_exercise')->default(false)->after('is_active');
            $table->boolean('has_speaking_exercise')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('has_listening_exercise');
        });

        Schema::table('course_levels', function (Blueprint $table) {
            $table->dropColumn(['has_listening_exercise', 'has_writing_exercise', 'has_speaking_exercise']);
        });
    }
};
