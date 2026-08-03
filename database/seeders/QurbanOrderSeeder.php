<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QurbanOrderSeeder extends Seeder
{
    // database/seeders/QurbanOrderSeeder.php
    public function run(): void
    {
        $sapiPatungan = \App\Models\SacrificialAnimal::where('name', 'SAPI-002')->first();
        $users = \App\Models\User::role('jamaah')->inRandomOrder()->limit(5)->get();

        $order = \App\Models\QurbanOrder::create([
            'user_id' => $users->first()->id,
            'sacrificial_animal_id' => $sapiPatungan->id,
            'order_type' => 'patungan',
            'total_amount' => $sapiPatungan->price,
            'midtrans_order_id' => 'QRB-' . now()->format('Ymd') . '-0001',
            'payment_status' => 'success',
            'paid_at' => now()->subDays(3),
        ]);

        foreach ($users as $slot => $user) {
            \App\Models\QurbanParticipant::create([
                'qurban_order_id' => $order->id,
                'user_id' => $user->id,
                'slot_number' => $slot + 1,
                'share_amount' => round($sapiPatungan->price / 7, 2),
            ]);
        }

        // beberapa pesanan kambing dengan status bervariasi (pending, success, failed, expired)
        $statuses = ['pending', 'success', 'failed', 'expired'];
        foreach ($statuses as $i => $status) {
            \App\Models\QurbanOrder::create([
                'user_id' => \App\Models\User::role('jamaah')->inRandomOrder()->first()->id,
                'sacrificial_animal_id' => \App\Models\SacrificialAnimal::where('animal_type', 'kambing')->inRandomOrder()->first()->id,
                'order_type' => 'full',
                'total_amount' => rand(2500000, 4500000),
                'midtrans_order_id' => 'QRB-' . now()->format('Ymd') . '-000' . ($i + 2),
                'payment_status' => $status,
                'paid_at' => $status === 'success' ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}
