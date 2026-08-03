<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['qurban_orders', 'infaqs', 'zakats'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('snap_token')->nullable()->after('midtrans_order_id');
                $table->timestamp('snap_token_expires_at')->nullable()->after('snap_token');
            });
        }
    }

    public function down(): void
    {
        foreach (['qurban_orders', 'infaqs', 'zakats'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['snap_token', 'snap_token_expires_at']);
            });
        }
    }
};
