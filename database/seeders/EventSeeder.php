<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventDocumentation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed event categories
        $categories = ['Kajian Rutin', 'Peringatan Hari Besar Islam', 'Sosial & Bakti Masyarakat', 'Pendidikan'];
        foreach ($categories as $cat) {
            EventCategory::firstOrCreate(
                ['name' => $cat],
                ['slug' => Str::slug($cat)]
            );
        }

        // Get admin user
        $adminUser = \App\Models\User::role('admin')->first();

        if (!$adminUser) {
            $this->command->error('No admin user found. Please create an admin user first.');
            return;
        }

        // Event data
        $events = [
            [
                'title' => 'Kajian Subuh: Meneladani Akhlak Rasulullah',
                'start_at' => now()->subDays(20),
                'end_at' => now()->subDays(20)->addHours(2),
                'status' => 'published',
                'registration_status' => 'closed',
                'location' => 'Aula Utama Masjid Al-Ikhlas, Jl. Merdeka No. 45',
                'speaker_name' => 'Ustadz Dr. Ahmad Fauzi, M.Ag',
                'excerpt' => 'Kajian subuh rutin membahas akhlak Rasulullah SAW',
            ],
            [
                'title' => 'Peringatan Maulid Nabi Muhammad SAW 1447 H',
                'start_at' => now()->subDays(10),
                'end_at' => now()->subDays(10)->addHours(4),
                'status' => 'published',
                'registration_status' => 'closed',
                'location' => 'Masjid Al-Ikhlas & Halaman Depan, Jl. Merdeka No. 45',
                'speaker_name' => 'Ustadz H. Muhammad Syafi\'i, Lc., M.A',
                'excerpt' => 'Peringatan Maulid Nabi dengan pengajian akbar dan santunan anak yatim',
            ],
            [
                'title' => 'Bakti Sosial: Pembagian Sembako untuk Dhuafa',
                'start_at' => now()->subDays(5),
                'end_at' => now()->subDays(5)->addHours(6),
                'status' => 'published',
                'registration_status' => 'closed',
                'location' => 'Masjid Al-Ikhlas & Sekitarnya, Jl. Merdeka No. 45',
                'speaker_name' => 'Panitia Sosial Masjid Al-Ikhlas',
                'excerpt' => 'Pembagian 100 paket sembako untuk masyarakat dhuafa sekitar masjid',
            ],
            [
                'title' => 'Pelatihan Tahsin Al-Qur\'an untuk Remaja',
                'start_at' => now()->addDays(7),
                'end_at' => now()->addDays(7)->addHours(3),
                'status' => 'published',
                'registration_status' => 'open',
                'location' => 'Ruang Belajar Masjid Al-Ikhlas, Lt. 2',
                'speaker_name' => 'Ustadzah Lailatul Qadriyah, S.Pd.I',
                'excerpt' => 'Pelatihan tahsin Al-Qur\'an khusus untuk remaja usia 13-17 tahun',
            ],
            [
                'title' => 'Kajian Tematik: Membangun Keluarga Sakinah',
                'start_at' => now()->addDays(14),
                'end_at' => now()->addDays(14)->addHours(2),
                'status' => 'published',
                'registration_status' => 'open',
                'location' => 'Aula Utama Masjid Al-Ikhlas, Jl. Merdeka No. 45',
                'speaker_name' => 'Ustadz Dr. Abdul Hakim, M.Psi',
                'excerpt' => 'Kajian tentang membangun keluarga sakinah di era modern',
            ],
            [
                'title' => 'Rapat Koordinasi Panitia Kurban 1448 H',
                'start_at' => now()->addDays(30),
                'end_at' => now()->addDays(30)->addHours(3),
                'status' => 'draft',
                'registration_status' => 'open',
                'location' => 'Ruang Rapat Masjid Al-Ikhlas',
                'speaker_name' => 'Ketua Panitia Kurban',
                'excerpt' => 'Rapat koordinasi persiapan pelaksanaan ibadah kurban',
            ],
        ];

        // Create events
        foreach ($events as $i => $ev) {
            $category = EventCategory::inRandomOrder()->first();

            $event = Event::create([
                'category_id' => $category ? $category->id : null, // Ganti dari event_category_id
                'title' => $ev['title'],
                'slug' => Str::slug($ev['title']),
                'excerpt' => $ev['excerpt'] ?? fake()->sentence(10),
                'description' => fake()->paragraphs(3, true),
                'location' => $ev['location'],
                'speaker_name' => $ev['speaker_name'] ?? 'Ustadz/Ustadzah Masjid Al-Ikhlas',
                'latitude' => null,
                'longitude' => null,
                'start_at' => $ev['start_at'], // Ganti dari schedule
                'end_at' => $ev['end_at'], // Tambahkan end_at
                'thumbnail' => 'seed-images/events/event-' . ($i + 1) . '.jpg',
                'poster' => null,
                'registration_status' => $ev['registration_status'],
                'status' => $ev['status'],
                'is_featured' => $i < 3 ? true : false, // 3 event pertama di-featured
                'views_count' => $ev['status'] === 'published' ? rand(10, 100) : 0,
            ]);

            // Create documentation only for events that have already occurred
            if ($ev['start_at'] < now()) {
                $photoCount = rand(2, 4);
                for ($j = 1; $j <= $photoCount; $j++) {
                    EventDocumentation::create([
                        'event_id' => $event->id,
                        'photo' => 'seed-images/events/dokumentasi/event-' . ($i + 1) . '-foto-' . $j . '.jpg',
                        'caption' => 'Dokumentasi kegiatan ' . $ev['title'],
                    ]);
                }
            }
        }

        $this->command->info('Events seeded successfully!');
    }
}
