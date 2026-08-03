<?php

namespace Database\Seeders;

use App\Models\MosqueProfile;
use Illuminate\Database\Seeder;

class MosqueProfileSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\MosqueProfile::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Masjid Jami\' AN-NUR',
                'history' => 'Masjid Jami\' An-Nur didirikan pada tahun 1985 atas prakarsa warga sekitar yang ingin memiliki tempat ibadah representatif. Bermula dari musala kecil berukuran 6x8 meter, masjid ini mengalami tiga kali renovasi besar hingga mampu menampung lebih dari 500 jamaah pada tahun 2010. Hingga kini, Masjid An-Nur terus berkembang sebagai pusat kegiatan ibadah, pendidikan, dan sosial bagi masyarakat sekitar.',
                'vision' => 'Menjadi pusat pembinaan umat yang unggul dalam ibadah, pendidikan, dan pemberdayaan sosial berlandaskan Al-Qur\'an dan Sunnah.',
                'mission' => "1. Menyelenggarakan kegiatan ibadah yang tertib dan nyaman.\n2. Mengadakan kajian rutin dan pendidikan Al-Qur'an bagi seluruh usia.\n3. Mengelola dana infaq, zakat, dan kurban secara transparan dan akuntabel.\n4. Menjadi wadah silaturahmi dan pemberdayaan ekonomi jamaah.",
                'address' => 'Jl. Merdeka No. 45, Kelurahan Sukamaju, Kecamatan Ciputat, Kota Tangerang Selatan, Banten 15412',
                'contact' => '0812-3456-7890',
                'hero_image' => 'seed-images/mosque/hero.jpg',
                'bank_account_number' => 'BSI 7123456789 a.n. DKM Masjid An-Nur',
                'latitude' => -6.2929,
                'longitude' => 106.7180,
            ]
        );
    }
}
