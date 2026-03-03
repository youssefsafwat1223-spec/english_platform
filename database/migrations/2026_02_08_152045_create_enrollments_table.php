<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('price_paid', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->enum('discount_type', ['referral', 'coupon', 'promotion'])->nullable();
            $table->string('discount_code', 100)->nullable();
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->integer('completed_lessons')->default(0);
            $table->integer('total_lessons');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('certificate_issued_at')->nullable();
            $table->string('certificate_id', 100)->unique()->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            // Indexes and constraints
            $table->unique(['user_id', 'course_id']);
            $table->index('user_id');
            $table->index('course_id');
            $table->index('certificate_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};