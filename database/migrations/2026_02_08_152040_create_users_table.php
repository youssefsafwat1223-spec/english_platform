<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->unique();
            $table->string('telegram_username')->nullable();
            $table->string('telegram_chat_id')->unique()->nullable();
            $table->timestamp('telegram_linked_at')->nullable();
            $table->enum('role', ['student', 'admin'])->default('student');
            $table->string('avatar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('total_points')->default(0);
            $table->integer('current_streak')->default(0);
            $table->integer('longest_streak')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->string('referral_code', 50)->unique();
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('referral_discount_used')->default(false);
            $table->timestamp('referral_discount_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('email');
            $table->index('phone');
            $table->index('telegram_chat_id');
            $table->index('referral_code');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};