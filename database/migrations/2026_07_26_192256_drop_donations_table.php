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
        // Hapus tabel donations (sudah digantikan oleh infaqs & zakats)
        Schema::dropIfExists('donations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika rollback, buat ulang tabel donations (opsional)
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['infaq', 'zakat']);
            $table->decimal('amount', 15, 2);
            $table->string('donor_name')->nullable();
            $table->text('message')->nullable();
            $table->string('midtrans_order_id')->unique();
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
