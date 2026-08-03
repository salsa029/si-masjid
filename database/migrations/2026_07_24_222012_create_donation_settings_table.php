<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('qris_image')->nullable();
            $table->decimal('min_infaq_amount', 15, 2)->default(10000);
            $table->decimal('min_zakat_amount', 15, 2)->default(10000);
            $table->decimal('rice_price_per_kg', 15, 2)->default(15000); // dipakai kalkulator Zakat Fitrah
            $table->decimal('gold_price_per_gram', 15, 2)->default(1200000); // dipakai kalkulator Zakat Maal
            $table->text('thank_you_message')->nullable();
            $table->string('confirmation_whatsapp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_settings');
    }
};
