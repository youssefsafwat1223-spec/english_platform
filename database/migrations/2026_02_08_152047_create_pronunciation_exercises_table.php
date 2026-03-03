<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pronunciation_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->text('sentence_1');
            $table->text('sentence_2')->nullable();
            $table->text('sentence_3')->nullable();
            $table->string('reference_audio_1')->nullable();
            $table->string('reference_audio_2')->nullable();
            $table->string('reference_audio_3')->nullable();
            $table->integer('passing_score')->default(70)->comment('percentage');
            $table->integer('max_duration_seconds')->default(10);
            $table->boolean('allow_retake')->default(true);
            $table->timestamps();

            $table->index('lesson_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pronunciation_exercises');
    }
};