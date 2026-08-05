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
            // Null = pakai gambar latar default (resources/certificate-assets/qurban-certificate-bg.png).
            $table->string('certificate_background')->nullable()->after('qurban_chairman_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->dropColumn('certificate_background');
        });
    }
};
