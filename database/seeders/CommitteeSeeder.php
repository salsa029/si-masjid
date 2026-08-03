<?php

namespace Database\Seeders;

use App\Models\Committee;
use Illuminate\Database\Seeder;

class CommitteeSeeder extends Seeder
{
    // database/seeders/CommitteeSeeder.php
    public function run(): void
    {
        $data = [
            ['name' => 'H. Abdul Karim, S.Pd.I', 'position' => 'Ketua DKM', 'photo' => 'seed-images/committees/ketua.jpg', 'bio' => 'Menjabat sebagai Ketua DKM sejak 2022, aktif dalam pengembangan program dakwah dan sosial masjid.'],
            ['name' => 'Drs. Bambang Hidayat', 'position' => 'Wakil Ketua DKM', 'photo' => 'seed-images/committees/wakil.jpg', 'bio' => 'Membantu koordinasi program kerja dan hubungan dengan warga sekitar.'],
            ['name' => 'Ahmad Fauzan, S.E.', 'position' => 'Sekretaris', 'photo' => 'seed-images/committees/sekretaris.jpg', 'bio' => 'Bertanggung jawab atas administrasi dan kesekretariatan masjid.'],
            ['name' => 'Siti Aminah', 'position' => 'Bendahara', 'photo' => 'seed-images/committees/bendahara.jpg', 'bio' => 'Mengelola keuangan masjid termasuk dana infaq, zakat, dan kurban secara transparan.'],
            ['name' => 'Ust. Yusuf Mansur Hidayat, Lc.', 'position' => 'Ketua Bidang Dakwah', 'photo' => 'seed-images/committees/dakwah.jpg', 'bio' => 'Mengoordinasikan kajian rutin, kultum, dan program pendidikan Al-Qur\'an.'],
            ['name' => 'Rudi Setiawan', 'position' => 'Ketua Bidang Humas', 'photo' => 'seed-images/committees/humas.jpg', 'bio' => 'Mengelola publikasi kegiatan masjid ke media sosial dan warga sekitar.'],
            ['name' => 'H. Sutrisno', 'position' => 'Ketua Bidang Kurban', 'photo' => 'seed-images/committees/kurban.jpg', 'bio' => 'Mengoordinasikan pelaksanaan qurban tiap tahun mulai dari pendaftaran hingga distribusi.'],
        ];

        foreach ($data as $item) {
            \App\Models\Committee::create(array_merge($item, [
                'term_start' => '2022-01-01',
                'term_end' => '2026-12-31',
            ]));
        }
    }
}
