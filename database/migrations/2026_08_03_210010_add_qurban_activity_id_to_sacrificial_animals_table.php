<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sacrificial_animals', function (Blueprint $table) {
            $table->foreignId('qurban_activity_id')->nullable()->after('id')
                ->constrained('qurban_activities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sacrificial_animals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('qurban_activity_id');
        });
    }
};
