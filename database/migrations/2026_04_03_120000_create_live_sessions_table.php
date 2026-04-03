<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('zoom_join_url');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->string('status', 20)->default('draft');
            $table->boolean('banner_enabled')->default(true);
            $table->boolean('notifications_enabled')->default(true);
            $table->text('recording_url')->nullable();
            $table->dateTime('published_notification_sent_at')->nullable();
            $table->dateTime('notified_24h_at')->nullable();
            $table->dateTime('notified_1h_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'starts_at']);
            $table->index('banner_enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_sessions');
    }
};
