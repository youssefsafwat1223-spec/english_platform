<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('installment_plan_id')->nullable()->after('promo_code_id')
                ->constrained('installment_plans')->nullOnDelete();
            $table->tinyInteger('installment_number')->nullable()->after('installment_plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['installment_plan_id']);
            $table->dropColumn(['installment_plan_id', 'installment_number']);
        });
    }
};
