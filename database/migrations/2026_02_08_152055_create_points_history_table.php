<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points_earned');
            $table->string('activity_type', 100)->comment('lesson_complete, quiz_pass, etc');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('activity_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_history');
    }
};