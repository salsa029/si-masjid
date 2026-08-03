<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qurban_orders', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('midtrans_order_id');
            $table->string('certificate_number')->nullable()->unique()->after('invoice_number');
            $table->enum('payment_method', ['midtrans', 'manual_transfer'])->nullable()->after('payment_status');
            $table->string('payment_proof')->nullable()->after('payment_method');
            $table->text('verification_note')->nullable()->after('payment_proof');
            $table->foreignId('verified_by')->nullable()->after('verification_note')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->timestamp('reserved_until')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('qurban_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'invoice_number',
                'certificate_number',
                'payment_method',
                'payment_proof',
                'verification_note',
                'verified_at',
                'reserved_until',
            ]);
        });
    }
};
