<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_id');
            $table->index('order_index');
            $table->unique(['course_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_levels');
    }
};
