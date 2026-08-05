<?php

namespace Database\Seeders;

use App\Models\QurbanActivity;
use App\Models\SacrificialAnimal;
use App\Models\SlaughterDocumentation;
use Illuminate\Database\Seeder;

class SacrificialAnimalSeeder extends Seeder
{
    // database/seeders/SacrificialAnimalSeeder.php
    public function run(): void
    {
        // Belum ada file foto contoh yang benar-benar tersedia di storage,
        // jadi photo sengaja dibiarkan null (bukan path palsu) supaya UI
        // menampilkan placeholder "belum ada foto", bukan ikon gambar rusak.
        $qurbanActivityId = QurbanActivity::query()->value('id');

        // 4 sapi: 1 tersedia, 1 penuh (patungan 7 orang), 1 sudah disembelih, 1 masih kosong
        $sapiData = [
            ['name' => 'SAPI-001', 'status' => 'available'],
            ['name' => 'SAPI-002', 'status' => 'fully_booked'],
            ['name' => 'SAPI-003', 'status' => 'slaughtered'],
            ['name' => 'SAPI-004', 'status' => 'available'],
        ];
        foreach ($sapiData as $d) {
            $animal = SacrificialAnimal::create(array_merge($d, [
                'animal_type' => 'sapi',
                'weight' => fake()->randomFloat(2, 300, 400),
                'age' => rand(24, 36),
                'price' => rand(18000000, 25000000),
                'max_participants' => 7,
                'qurban_activity_id' => $qurbanActivityId,
            ]));

            if ($d['status'] === 'slaughtered') {
                SlaughterDocumentation::create([
                    'sacrificial_animal_id' => $animal->id,
                    'photo' => 'seed-images/animals/slaughter/dokumentasi-2.jpg',
                    'description' => 'Proses penyembelihan dilakukan sesuai syariat, disaksikan oleh panitia dan perwakilan pekurban.',
                ]);
            }
        }

        // 4 kambing: kombinasi status serupa
        foreach (range(1, 4) as $i) {
            SacrificialAnimal::create([
                'animal_type' => 'kambing',
                'name' => 'KAMBING-00' . $i,
                'weight' => fake()->randomFloat(2, 25, 40),
                'age' => rand(12, 24),
                'price' => rand(2500000, 4500000),
                'max_participants' => 1,
                'status' => $i === 1 ? 'slaughtered' : 'available',
                'qurban_activity_id' => $qurbanActivityId,
            ]);
        }
    }
}
