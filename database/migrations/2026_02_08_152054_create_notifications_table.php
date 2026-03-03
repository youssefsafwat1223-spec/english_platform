<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('notification_type', 100)->comment('quiz_result, comment_reply, etc');
            $table->string('title');
            $table->text('message');
            $table->text('action_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('sent_to_telegram')->default(false);
            $table->timestamp('telegram_sent_at')->nullable();
            $table->string('telegram_message_id')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('is_read');
            $table->index('notification_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};