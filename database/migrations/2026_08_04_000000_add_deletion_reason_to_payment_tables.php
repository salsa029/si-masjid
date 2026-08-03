<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['infaqs', 'zakats', 'qurban_orders'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('deletion_reason')->nullable()->after('verification_note');
            });
        }
    }

    public function down(): void
    {
        foreach (['infaqs', 'zakats', 'qurban_orders'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('deletion_reason');
            });
        }
    }
};
