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
        Schema::table('qurban_orders', function (Blueprint $table) {
            $table->enum('payment_type', ['full', 'installment'])->default('full')->after('order_type');
            $table->unsignedTinyInteger('installment_count')->nullable()->after('payment_type');
            $table->date('installment_deadline')->nullable()->after('installment_count');
            $table->boolean('refund_requested')->default(false)->after('installment_deadline');
            $table->timestamp('refund_requested_at')->nullable()->after('refund_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'installment_count',
                'installment_deadline',
                'refund_requested',
                'refund_requested_at',
            ]);
        });
    }
};
