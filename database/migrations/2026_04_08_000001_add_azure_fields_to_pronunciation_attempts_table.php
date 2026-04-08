<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->unsignedTinyInteger('azure_accuracy_score')->nullable()->after('fluency_score');
            $table->unsignedTinyInteger('azure_fluency_score')->nullable()->after('azure_accuracy_score');
            $table->unsignedTinyInteger('azure_completeness_score')->nullable()->after('azure_fluency_score');
            $table->unsignedTinyInteger('azure_pronunciation_score')->nullable()->after('azure_completeness_score');
            $table->unsignedTinyInteger('azure_prosody_score')->nullable()->after('azure_pronunciation_score');
            $table->json('azure_response_json')->nullable()->after('stream_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->dropColumn([
                'azure_accuracy_score',
                'azure_fluency_score',
                'azure_completeness_score',
                'azure_pronunciation_score',
                'azure_prosody_score',
                'azure_response_json',
            ]);
        });
    }
};
