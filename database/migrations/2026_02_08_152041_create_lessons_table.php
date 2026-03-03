<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('video_url')->nullable();
            $table->integer('video_duration')->nullable()->comment('in seconds');
            $table->longText('text_content')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('has_quiz')->default(true);
            $table->boolean('has_pronunciation_exercise')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('course_id');
            $table->index('order_index');
            $table->unique(['course_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};