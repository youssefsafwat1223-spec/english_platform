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
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('tap_charge_id', 'gateway_payment_id');
            $table->renameColumn('tap_response', 'gateway_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('gateway_payment_id', 'tap_charge_id');
            $table->renameColumn('gateway_response', 'tap_response');
        });
    }
};
