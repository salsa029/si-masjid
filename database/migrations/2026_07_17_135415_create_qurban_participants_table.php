<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qurban_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qurban_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('slot_number');
            $table->decimal('share_amount', 15, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['qurban_order_id', 'slot_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qurban_participants');
    }
};
