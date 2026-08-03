<?php

namespace Database\Seeders;

use App\Models\SacrificialAnimal;
use Illuminate\Database\Seeder;

class SacrificialAnimalSeeder extends Seeder
{
    // database/seeders/SacrificialAnimalSeeder.php
    public function run(): void
    {
        // 4 sapi: 1 tersedia, 1 penuh (patungan 7 orang), 1 sudah disembelih, 1 masih kosong
        $sapiData = [
            ['name' => 'SAPI-001', 'status' => 'available', 'photo' => 'seed-images/animals/sapi-1.jpg'],
            ['name' => 'SAPI-002', 'status' => 'fully_booked', 'photo' => 'seed-images/animals/sapi-2.jpg'],
            ['name' => 'SAPI-003', 'status' => 'slaughtered', 'photo' => 'seed-images/animals/sapi-3.jpg'],
            ['name' => 'SAPI-004', 'status' => 'available', 'photo' => 'seed-images/animals/sapi-4.jpg'],
        ];
        foreach ($sapiData as $d) {
            $animal = \App\Models\SacrificialAnimal::create(array_merge($d, [
                'animal_type' => 'sapi',
                'weight' => fake()->randomFloat(2, 300, 400),
                'age' => rand(24, 36),
                'price' => rand(18000000, 25000000),
                'max_participants' => 7,
            ]));

            if ($d['status'] === 'slaughtered') {
                \App\Models\SlaughterDocumentation::create([
                    'sacrificial_animal_id' => $animal->id,
                    'photo' => 'seed-images/animals/slaughter/dokumentasi-1.jpg',
                    'description' => 'Proses penyembelihan dilakukan sesuai syariat, disaksikan oleh panitia dan perwakilan pekurban.',
                ]);
            }
        }

        // 4 kambing: kombinasi status serupa
        foreach (range(1, 4) as $i) {
            \App\Models\SacrificialAnimal::create([
                'animal_type' => 'kambing',
                'name' => 'KAMBING-00' . $i,
                'weight' => fake()->randomFloat(2, 25, 40),
                'age' => rand(12, 24),
                'price' => rand(2500000, 4500000),
                'max_participants' => 1,
                'status' => $i === 1 ? 'slaughtered' : 'available',
                'photo' => 'seed-images/animals/kambing-' . $i . '.jpg',
            ]);
        }
    }
}
