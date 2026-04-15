<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('installment_amount', 10, 2); // total / 3
            $table->tinyInteger('installments_count')->default(3);
            $table->tinyInteger('installments_paid')->default(0);
            $table->timestamp('next_due_at')->nullable();
            $table->enum('status', ['active', 'completed', 'suspended', 'defaulted', 'cancelled'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
