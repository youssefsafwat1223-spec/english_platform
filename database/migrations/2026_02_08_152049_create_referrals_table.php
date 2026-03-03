<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade')->comment('user who shared link');
            $table->foreignId('referee_id')->constrained('users')->onDelete('cascade')->comment('user who signed up');
            $table->string('referral_code', 50);
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('first_purchase_at')->nullable();
            $table->decimal('first_purchase_amount', 10, 2)->nullable();
            $table->boolean('referrer_discount_earned')->default(false);
            $table->boolean('referrer_discount_used')->default(false);
            $table->timestamp('referrer_discount_used_at')->nullable();
            $table->boolean('referee_discount_used')->default(false);
            $table->timestamp('referee_discount_used_at')->nullable();
            $table->enum('status', ['clicked', 'registered', 'purchased'])->default('clicked');
            $table->timestamps();

            // Indexes
            $table->index('referrer_id');
            $table->index('referee_id');
            $table->index('referral_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};