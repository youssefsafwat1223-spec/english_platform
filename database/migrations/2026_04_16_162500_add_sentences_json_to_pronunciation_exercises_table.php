<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->json('sentences_json')->nullable()->after('sentence_3');
        });
    }

    public function down(): void
    {
        Schema::table('pronunciation_exercises', function (Blueprint $table) {
            $table->dropColumn('sentences_json');
        });
    }
};
