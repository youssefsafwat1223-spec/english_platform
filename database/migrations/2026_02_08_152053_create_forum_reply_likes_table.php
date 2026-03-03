<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_reply_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reply_id')->constrained('forum_replies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Indexes and constraints
            $table->unique(['user_id', 'reply_id']);
            $table->index('reply_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_reply_likes');
    }
};