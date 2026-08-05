<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('position');
        });

        // Backfill: pertahankan urutan tampil yang sudah ada (berdasarkan id)
        // supaya admin tidak melihat urutan berubah setelah migration ini.
        DB::table('committees')->orderBy('id')->get(['id'])->each(function ($committee, $index) {
            DB::table('committees')->where('id', $committee->id)->update(['sort_order' => $index]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
