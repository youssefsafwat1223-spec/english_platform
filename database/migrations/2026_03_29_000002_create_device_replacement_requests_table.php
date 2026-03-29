<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_replacement_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('requested_device_token_hash', 64);
            $table->string('device_label')->nullable();
            $table->string('device_type', 20)->default('unknown');
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('replacement_for_device_id')->nullable()->constrained('user_devices')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status'], 'dev_req_user_status_idx');
            $table->index(['requested_device_token_hash', 'status'], 'dev_req_token_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_replacement_requests');
    }
};
