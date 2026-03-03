<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('total_topics')->default(0);
            $table->integer('total_posts')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('order_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_categories');
    }
};