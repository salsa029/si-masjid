<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Sengaja kosong: nilai enum 'draft', 'published', 'archived' pada kolom
     * `status` sudah didefinisikan langsung di migration create_articles_table,
     * sehingga tidak ada perubahan skema yang perlu dijalankan di sini.
     */
    public function up(): void
    {
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
