<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('forum_topics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->longText('content');
            $table->boolean('is_solution')->default(false);
            $table->integer('like_count')->default(0);
            $table->boolean('is_reported')->default(false);
            $table->integer('report_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('topic_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_replies');
    }
};