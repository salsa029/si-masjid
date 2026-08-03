<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->randomElement([
            'Tabligh Akbar Rutin',
            'Santunan Anak Yatim',
            'Pelatihan Imam Muda',
            'Kajian Subuh Jamaah',
            'Bakti Sosial Ramadhan'
        ]) . ' ' . $this->faker->year();

        $status = $this->faker->randomElement(['draft', 'published']);

        // Buat Carbon instance untuk start_at
        $startAt = Carbon::parse($this->faker->dateTimeBetween('-1 month', '+2 months'));
        // end_at dibuat 2 jam setelah start_at
        $endAt = (clone $startAt)->addHours(2);

        // Logika status pendaftaran berdasarkan tanggal
        $registrationStatus = $startAt->isPast()
            ? 'closed'
            : $this->faker->randomElement(['open', 'full']);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'description' => collect($this->faker->paragraphs(3))->map(fn($p) => "<p>$p</p>")->implode("\n"),
            'excerpt' => $this->faker->sentence(10),
            'location' => $this->faker->randomElement(['Ruang Utama Masjid', 'Aula Lantai 2', 'Halaman Utama']),
            'speaker_name' => $this->faker->name(),
            'start_at' => $startAt,
            'end_at' => $endAt,
            'thumbnail' => 'placeholders/kegiatan.jpg',
            'registration_status' => $registrationStatus,
            'status' => $status,
            'is_featured' => $this->faker->boolean(20), // 20% peluang jadi featured
            'views_count' => $this->faker->numberBetween(0, 500),
        ];
    }
}
