<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('zakat_type_id')->constrained('zakat_types')->restrictOnDelete();
            $table->string('transaction_number')->nullable()->unique();
            $table->string('midtrans_order_id')->unique();
            $table->string('muzakki_name')->nullable();
            $table->unsignedInteger('number_of_souls')->nullable(); // khusus Zakat Fitrah
            $table->decimal('calculation_base', 15, 2)->nullable(); // total harta yang dihitung, khusus Zakat Maal/Penghasilan
            $table->boolean('is_above_nishab')->nullable();
            $table->decimal('amount', 15, 2);
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->enum('payment_method', ['midtrans', 'manual_transfer'])->nullable();
            $table->string('payment_proof')->nullable();
            $table->enum('payment_status', ['pending', 'awaiting_verification', 'success', 'failed', 'expired'])->default('pending');
            $table->text('verification_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('reserved_until')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakats');
    }
};
