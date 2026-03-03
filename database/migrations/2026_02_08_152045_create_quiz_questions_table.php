<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->integer('order_index')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('quiz_id');
            $table->unique(['quiz_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};