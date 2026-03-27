<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('promo_code_id')
                ->nullable()
                ->after('course_id')
                ->constrained('promo_codes')
                ->nullOnDelete();
            $table->string('discount_type', 50)->nullable()->after('discount_amount');
            $table->string('discount_code', 100)->nullable()->after('discount_type');
            $table->timestamp('benefits_processed_at')->nullable()->after('paid_at');

            $table->index('promo_code_id');
            $table->index('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['promo_code_id']);
            $table->dropIndex(['discount_type']);
            $table->dropConstrainedForeignId('promo_code_id');
            $table->dropColumn([
                'discount_type',
                'discount_code',
                'benefits_processed_at',
            ]);
        });
    }
};
