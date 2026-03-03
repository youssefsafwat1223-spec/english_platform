<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->string('certificate_id', 100)->unique()->comment('e.g., EGC-2026-0245');
            $table->string('certificate_type', 50)->default('completion');
            $table->integer('final_score')->comment('percentage');
            $table->string('pdf_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->text('verification_url')->nullable();
            $table->timestamp('issued_at');
            $table->timestamp('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('linkedin_shared')->default(false);
            $table->timestamp('linkedin_shared_at')->nullable();
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
        Schema::dropIfExists('certificates');
    }
};