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
        Schema::table('qurban_activities', function (Blueprint $table) {
            // Posisi dalam mm dari pojok kiri-atas halaman A4 landscape (297x210mm), ukuran font dalam px.
            // Default-nya dibuat SAMA PERSIS dengan nilai CSS hardcoded sebelumnya, supaya sertifikat
            // yang sudah ada tidak berubah tampilan sampai admin sengaja mengubahnya.
            $table->decimal('certificate_name_top', 5, 2)->default(87.00)->after('qurban_chairman_photo');
            $table->decimal('certificate_name_left', 5, 2)->default(53.00)->after('certificate_name_top');
            $table->unsignedSmallInteger('certificate_name_font_size')->default(36)->after('certificate_name_left');

            $table->decimal('certificate_year_top', 5, 2)->default(55.00)->after('certificate_name_font_size');
            $table->decimal('certificate_year_left', 5, 2)->default(166.00)->after('certificate_year_top');
            $table->unsignedSmallInteger('certificate_year_font_size')->default(30)->after('certificate_year_left');

            $table->decimal('certificate_animal_top', 5, 2)->default(110.00)->after('certificate_year_font_size');
            $table->decimal('certificate_animal_left', 5, 2)->default(179.00)->after('certificate_animal_top');
            $table->unsignedSmallInteger('certificate_animal_font_size')->default(21)->after('certificate_animal_left');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_name_top',
                'certificate_name_left',
                'certificate_name_font_size',
                'certificate_year_top',
                'certificate_year_left',
                'certificate_year_font_size',
                'certificate_animal_top',
                'certificate_animal_left',
                'certificate_animal_font_size',
            ]);
        });
    }
};
