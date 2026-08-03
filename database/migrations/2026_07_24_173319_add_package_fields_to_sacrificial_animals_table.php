<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sacrificial_animals', function (Blueprint $table) {
            $table->string('package_name')->nullable()->after('name');
            $table->text('package_description')->nullable()->after('package_name');
        });
    }

    public function down(): void
    {
        Schema::table('sacrificial_animals', function (Blueprint $table) {
            $table->dropColumn(['package_name', 'package_description']);
        });
    }
};
