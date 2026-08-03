<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slaughter_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sacrificial_animal_id')->constrained()->cascadeOnDelete();
            $table->string('photo');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slaughter_documentations');
    }
};
