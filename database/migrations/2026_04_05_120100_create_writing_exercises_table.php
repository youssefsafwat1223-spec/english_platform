<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writing_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('prompt');
            $table->text('instructions')->nullable();
            $table->unsignedSmallInteger('min_words')->default(30);
            $table->unsignedSmallInteger('max_words')->default(180);
            $table->unsignedTinyInteger('passing_score')->default(70);
            $table->text('model_answer')->nullable();
            $table->json('rubric_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writing_exercises');
    }
};
