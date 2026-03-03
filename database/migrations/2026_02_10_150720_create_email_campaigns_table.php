<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->enum('target_audience', ['all', 'active', 'inactive', 'course_specific'])->default('all');
            $table->foreignId('target_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->integer('recipients_count')->default(0);
            $table->integer('sent_count')->default(0);
            $table->enum('status', ['draft', 'sending', 'sent', 'failed'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
