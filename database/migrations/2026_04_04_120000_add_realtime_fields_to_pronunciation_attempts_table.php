<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->text('transcript_text')->nullable()->after('sentence_number');
            $table->text('expected_text')->nullable()->after('transcript_text');
            $table->json('word_diff_json')->nullable()->after('expected_text');
            $table->unsignedTinyInteger('completion_percent')->nullable()->after('fluency_score');
            $table->unsignedInteger('recognition_latency_ms')->nullable()->after('completion_percent');
            $table->string('stream_session_id', 100)->nullable()->after('recognition_latency_ms');

            $table->index('stream_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->dropIndex(['stream_session_id']);
            $table->dropColumn([
                'transcript_text',
                'expected_text',
                'word_diff_json',
                'completion_percent',
                'recognition_latency_ms',
                'stream_session_id',
            ]);
        });
    }
};

