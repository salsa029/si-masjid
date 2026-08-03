<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan seeder fondasi (Role, Admin, dan Profil)
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            MosqueProfileSeeder::class,
            CommitteeSeeder::class,
        ]);

        // 2. Buat User dummy tambahan terlebih dahulu sebelum membuat artikel
        User::factory(10)->create();

        // 3. Jalankan seeder yang membutuhkan data User atau data relasi lainnya
        $this->call([
            ArticleSeeder::class,
            EventSeeder::class,
            SacrificialAnimalSeeder::class,
        ]);
    }
}
