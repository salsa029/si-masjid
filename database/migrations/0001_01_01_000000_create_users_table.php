<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. MEMBUAT TABEL USERS (Menggunakan Schema::create, bukan Schema::table)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();

            // Ini adalah kolom-kolom tambahan dari kamu:
            $table->string('google_id')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();

            $table->timestamp('email_verified_at')->nullable();

            // Password dibuat nullable sesuai kebutuhan login Google kamu
            $table->string('password')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        // 2. TABEL BAWAAN LARAVEL (Biasanya ada di file yang sama di Laravel 10/11)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        // Menghapus tabel jika dilakukan rollback (migrate:rollback / migrate:fresh)
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
