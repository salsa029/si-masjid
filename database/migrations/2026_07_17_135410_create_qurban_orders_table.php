<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qurban_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sacrificial_animal_id')->constrained()->cascadeOnDelete();
            $table->enum('order_type', ['full', 'patungan']);
            $table->decimal('total_amount', 15, 2);
            $table->string('midtrans_order_id')->unique();

            // ✅ UBAH BARIS INI - Tambahkan 'awaiting_verification'
            $table->enum('payment_status', [
                'pending',
                'awaiting_verification',  // <-- Nilai baru
                'success',
                'failed',
                'expired'
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qurban_orders');
    }
};
