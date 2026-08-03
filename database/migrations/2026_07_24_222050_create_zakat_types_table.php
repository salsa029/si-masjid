<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Zakat Fitrah, Zakat Maal, Zakat Penghasilan
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('calculation_unit', ['fixed_per_soul', 'nishab_percentage'])->default('nishab_percentage');
            $table->decimal('nishab_amount', 15, 2)->nullable();
            $table->text('nishab_description')->nullable();
            $table->decimal('rate_percentage', 5, 2)->default(2.5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_types');
    }
};
