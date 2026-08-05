<?php

namespace Database\Seeders;

use App\Models\Infaq;
use App\Models\InfaqCampaign;
use App\Models\InfaqCategory;
use App\Models\User;
use App\Models\Zakat;
use App\Models\ZakatType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InfaqZakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Infaq Categories
        $kategoriInfaq = ['Infaq Pembangunan', 'Infaq Yatim Piatu', 'Infaq Operasional Masjid', 'Infaq Bencana Alam'];
        foreach ($kategoriInfaq as $k) {
            InfaqCategory::firstOrCreate(
                ['name' => $k],
                ['slug' => Str::slug($k)]
            );
        }

        // Create Infaq Campaign
        $infaqCategory = InfaqCategory::where('name', 'Infaq Pembangunan')->first();

        if (!$infaqCategory) {
            $this->command->error('Infaq category "Infaq Pembangunan" not found. Please run seeders in correct order.');
            return;
        }

        $campaign = InfaqCampaign::create([
            'category_id' => $infaqCategory->id,
            'title' => 'Renovasi Lantai 2 Masjid Al-Ikhlas',
            'slug' => 'renovasi-lantai-2-masjid-al-ikhlas',
            'description' => 'Penggalangan dana untuk perluasan lantai 2 masjid guna menampung jamaah yang terus bertambah, khususnya saat sholat Jumat dan Tarawih.',
            'target_amount' => 150000000,
            'thumbnail' => 'seed-images/events/event-1.jpg',
            'status' => 'active',
        ]);

        // Seed Zakat Types
        $jenisZakat = [
            [
                'name' => 'Zakat Fitrah',
                'formula_description' => '2,5 kg atau 3,5 liter beras per jiwa, atau senilai dengan uang.'
            ],
            [
                'name' => 'Zakat Maal (Harta)',
                'formula_description' => '2,5% dari total harta yang sudah mencapai nisab (setara 85 gram emas) dan haul (1 tahun).'
            ],
            [
                'name' => 'Zakat Penghasilan/Profesi',
                'formula_description' => '2,5% dari penghasilan bulanan/tahunan setelah dikurangi kebutuhan pokok.'
            ],
        ];

        foreach ($jenisZakat as $z) {
            ZakatType::firstOrCreate(
                ['name' => $z['name']],
                ['formula_description' => $z['formula_description']]
            );
        }

        // Get jamaah users
        $jamaahUsers = User::role('jamaah')->get();

        if ($jamaahUsers->isEmpty()) {
            $this->command->error('No jamaah users found. Please create jamaah users first.');
            return;
        }

        // Seed 20 Infaq transactions with date spread over 6 months and varied statuses
        $statuses = ['success', 'success', 'success', 'pending', 'failed', 'expired'];

        foreach (range(1, 20) as $i) {
            $status = $statuses[array_rand($statuses)];
            $createdAt = now()->subDays(rand(1, 180));
            $isAnonymous = rand(0, 4) === 0;

            Infaq::create([
                'user_id' => $jamaahUsers->random()->id,
                'campaign_id' => rand(0, 1) ? $campaign->id : null,
                'amount' => fake()->randomElement([20000, 50000, 100000, 250000, 500000, 1000000, 2000000]),
                'is_anonymous' => $isAnonymous,
                'donor_name' => $isAnonymous ? 'Hamba Allah' : null,
                'message' => fake()->boolean(60) ? 'Semoga menjadi amal jariyah dan berkah untuk masjid.' : null,
                'midtrans_order_id' => 'INF-' . $createdAt->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'payment_status' => $status,
                'paid_at' => $status === 'success' ? $createdAt : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // Seed 15 Zakat transactions
        foreach (range(1, 15) as $i) {
            $status = $statuses[array_rand($statuses)];
            $createdAt = now()->subDays(rand(1, 180));

            Zakat::create([
                'user_id' => $jamaahUsers->random()->id,
                'zakat_type_id' => ZakatType::inRandomOrder()->first()->id,
                'amount' => fake()->randomElement([45000, 500000, 1500000, 3000000]),
                'midtrans_order_id' => 'ZKT-' . $createdAt->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'payment_status' => $status,
                'paid_at' => $status === 'success' ? $createdAt : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('Infaq and Zakat data seeded successfully!');
        $this->command->info('- ' . Infaq::count() . ' infaq transactions created');
        $this->command->info('- ' . Zakat::count() . ' zakat transactions created');
    }
}
