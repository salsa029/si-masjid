<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    // database/seeders/UserSeeder.php
    public function run(): void
    {
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@simasjid.test'],
            [
                'name' => 'Muhammad Rizky Pratama',
                'password' => bcrypt('password'),
                'phone' => '0812-1111-2222',
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // 1 akun login via Google (tanpa password, ada google_id & avatar)
        $googleUser = \App\Models\User::updateOrCreate(
            ['email' => 'jamaah.google@gmail.com'],
            [
                'name' => 'Siti Nur Aisyah',
                'password' => null,
                'google_id' => '10987654321',
                'avatar' => 'https://lh3.googleusercontent.com/a/default-user',
                'email_verified_at' => now(),
            ]
        );
        $googleUser->assignRole('jamaah');

        // 15 akun jamaah biasa (untuk mengisi data donasi, komentar, pemesanan kurban, dsb.)
        \App\Models\User::factory()->count(15)->create()->each(function ($user) {
            $user->assignRole('jamaah');
        });
    }
}
