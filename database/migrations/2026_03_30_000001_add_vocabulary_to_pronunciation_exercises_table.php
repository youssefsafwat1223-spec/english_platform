<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            // 10 vocabulary words as JSON array: [{word, pronunciation, meaning_ar}]
            $table->json('vocabulary_json')->nullable()->after('sentence_3');
            // Explanation shown after student passes sentence_1 (passage)
            $table->text('passage_explanation')->nullable()->after('vocabulary_json');
            // Explanation shown after student passes sentence_2 (example sentence)
            $table->text('sentence_explanation')->nullable()->after('passage_explanation');
        });
    }

    public function down(): void
    {
        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->dropColumn(['vocabulary_json', 'passage_explanation', 'sentence_explanation']);
        });
    }
};
