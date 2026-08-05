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
            // Default sama persis dengan CSS hardcoded sebelumnya (.dkm-name / .panitia-name),
            // supaya sertifikat yang sudah ada tidak berubah tampilan.
            $table->decimal('certificate_dkm_name_top', 5, 2)->default(165.50)->after('certificate_animal_font_size');
            $table->decimal('certificate_dkm_name_left', 5, 2)->default(50.00)->after('certificate_dkm_name_top');
            $table->unsignedSmallInteger('certificate_dkm_name_font_size')->default(20)->after('certificate_dkm_name_left');

            $table->decimal('certificate_panitia_name_top', 5, 2)->default(165.50)->after('certificate_dkm_name_font_size');
            $table->decimal('certificate_panitia_name_left', 5, 2)->default(188.00)->after('certificate_panitia_name_top');
            $table->unsignedSmallInteger('certificate_panitia_name_font_size')->default(20)->after('certificate_panitia_name_left');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_dkm_name_top',
                'certificate_dkm_name_left',
                'certificate_dkm_name_font_size',
                'certificate_panitia_name_top',
                'certificate_panitia_name_left',
                'certificate_panitia_name_font_size',
            ]);
        });
    }
};
