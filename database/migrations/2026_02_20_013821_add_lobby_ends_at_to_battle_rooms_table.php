<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('battle_rooms', function (Blueprint $table) {
            $table->timestamp('lobby_ends_at')->nullable()->after('lobby_timer_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('battle_rooms', function (Blueprint $table) {
            //
        });
    }
};
