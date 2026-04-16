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
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_exam')->default(false)->after('title');
            $table->unsignedBigInteger('prerequisite_course_id')->nullable()->after('is_exam');

            $table->foreign('prerequisite_course_id')
                  ->references('id')
                  ->on('courses')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_course_id']);
            $table->dropColumn('prerequisite_course_id');
            $table->dropColumn('is_exam');
        });
    }
};
