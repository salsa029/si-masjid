<?php

namespace Database\Factories;

use App\Models\Committee;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommitteeFactory extends Factory
{
    protected $model = Committee::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 years', 'now');
        $end = (clone $start)->modify('+3 years');

        return [
            'name' => $this->faker->name(),
            'position' => $this->faker->randomElement(['Ketua DKM', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Seksi Peribadatan']),
            'photo' => 'placeholders/pengurus.jpg', // <-- Gunakan gambar placeholder pengurus
            'bio' => $this->faker->sentence(10),
            'term_start' => $start,
            'term_end' => $end,
        ];
    }
}
