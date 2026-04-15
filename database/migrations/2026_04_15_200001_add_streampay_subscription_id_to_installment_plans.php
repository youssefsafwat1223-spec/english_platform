<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            $table->string('streampay_subscription_id')->nullable()->unique()->after('status');
            $table->string('streampay_product_id')->nullable()->after('streampay_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('installment_plans', function (Blueprint $table) {
            $table->dropColumn(['streampay_subscription_id', 'streampay_product_id']);
        });
    }
};
