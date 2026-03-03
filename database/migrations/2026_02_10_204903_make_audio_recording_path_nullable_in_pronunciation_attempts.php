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
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->string('audio_recording_path')->nullable()->change();
            $table->integer('recording_duration')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->string('audio_recording_path')->nullable(false)->change();
            $table->integer('recording_duration')->nullable(false)->change();
        });
    }
};
