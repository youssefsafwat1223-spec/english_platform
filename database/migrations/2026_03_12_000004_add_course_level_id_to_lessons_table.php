<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('course_level_id')->nullable()->after('course_id')->constrained('course_levels')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['course_level_id']);
            $table->dropColumn('course_level_id');
        });
    }
};
