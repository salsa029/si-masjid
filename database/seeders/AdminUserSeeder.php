<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@masjid.test'],
            [
                'name' => 'Hafidz Sabila Rosyad',
                'password' => bcrypt('admin123'),
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}
