<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listening_exercises', function (Blueprint $table) {
            $table->id();
            // يدعم الاتنين — درس معين أو عنوان كامل
            $table->foreignId('lesson_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('course_level_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('script_ar');
            $table->text('script_display')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('audio_url')->nullable();
            $table->json('questions_json');
            $table->unsignedTinyInteger('passing_score')->default(70);
            $table->boolean('audio_generated')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listening_exercises');
    }
};
