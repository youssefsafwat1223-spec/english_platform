<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alter enum to add 'drag_drop'
        DB::statement("ALTER TABLE questions MODIFY COLUMN question_type ENUM('multiple_choice', 'true_false', 'fill_blank', 'drag_drop') NOT NULL");

        Schema::table('questions', function (Blueprint $table) {
            $table->json('matching_pairs')->nullable()->after('option_d')
                  ->comment('JSON array of {left, right} pairs for drag_drop type');
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE questions MODIFY COLUMN question_type ENUM('multiple_choice', 'true_false', 'fill_blank') NOT NULL");

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('matching_pairs');
        });
    }
};
