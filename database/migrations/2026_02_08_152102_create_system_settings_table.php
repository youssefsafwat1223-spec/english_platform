<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 100)->unique();
            $table->text('setting_value')->nullable();
            $table->string('setting_type', 50)->default('string');
            $table->string('setting_group', 100)->nullable()->comment('general, payment, email, etc');
            $table->boolean('is_public')->default(false);
            $table->text('description')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index('setting_key');
            $table->index('setting_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};