<?php

namespace Database\Factories;

use App\Models\MosqueProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class MosqueProfileFactory extends Factory
{
    protected $model = MosqueProfile::class;

    public function definition(): array
    {
        return [
            'name' => 'Masjid Raya ' . $this->faker->city(),
            'history' => $this->faker->paragraphs(3, true),
            'vision' => 'Menjadi pusat peradaban umat yang unggul dan inklusif.',
            'mission' => $this->faker->sentences(3, true),
            'address' => $this->faker->address(),
            'contact' => $this->faker->phoneNumber(),
            'hero_image' => 'hero_images/default.jpg', // atau $this->faker->imageUrl() jika ingin url gambar
            'bank_account_number' => $this->faker->bankAccountNumber(),
            'latitude' => $this->faker->latitude(-8.5, -6.0), // Contoh koordinat wilayah Indonesia
            'longitude' => $this->faker->longitude(106.0, 114.0),
        ];
    }
}
