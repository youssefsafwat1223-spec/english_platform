<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_live_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('live_session_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'live_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_live_session');
    }
};
