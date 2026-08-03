<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sacrificial_animals', function (Blueprint $table) {
            $table->id();
            $table->enum('animal_type', ['sapi', 'kambing', 'domba']);
            $table->string('name');
            $table->decimal('weight', 8, 2);
            $table->unsignedTinyInteger('age');
            $table->decimal('price', 15, 2);
            $table->string('photo')->nullable();
            $table->unsignedTinyInteger('max_participants')->default(1);
            $table->enum('status', ['available', 'fully_booked', 'slaughtered'])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sacrificial_animals');
    }
};
