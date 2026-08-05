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
        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('name');
            $table->date('end_date')->nullable()->after('start_date');
        });

        // Backfill periode dari kolom `date` lama (single day -> start_date = end_date = date lama)
        DB::table('qurban_activities')->update([
            'start_date' => DB::raw('date'),
            'end_date' => DB::raw('date'),
        ]);

        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->date('date')->nullable()->after('name');
        });

        DB::table('qurban_activities')->update([
            'date' => DB::raw('start_date'),
        ]);

        Schema::table('qurban_activities', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
