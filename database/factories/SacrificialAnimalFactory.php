<?php

namespace Database\Factories;

use App\Models\SacrificialAnimal;
use Illuminate\Database\Eloquent\Factories\Factory;

class SacrificialAnimalFactory extends Factory
{
    protected $model = SacrificialAnimal::class;

    public function definition(): array
    {
        $animalType = $this->faker->randomElement(['sapi', 'kambing', 'domba']);
        $status = $this->faker->randomElement(['available', 'fully_booked', 'slaughtered']);

        // Logika pengkondisian agar data dummy logis dan realistis
        if ($animalType === 'sapi') {
            $name = 'Sapi ' . $this->faker->randomElement(['Simmental', 'Limosin', 'Bali', 'PO']);
            $weight = $this->faker->randomFloat(2, 350, 800); // 350kg - 800kg
            $age = $this->faker->numberBetween(2, 4); // 2 - 4 tahun
            $price = $this->faker->randomFloat(2, 18000000, 45000000); // 18jt - 45jt
            $maxParticipants = 7; // Umumnya patungan sapi maksimal 7 orang
        } else {
            // Kambing atau Domba
            $breed = $animalType === 'kambing'
                ? $this->faker->randomElement(['Etawa', 'Muara', 'Kacang'])
                : $this->faker->randomElement(['Garut', 'Texel', 'Gembel']);

            $name = ucfirst($animalType) . ' ' . $breed;
            $weight = $this->faker->randomFloat(2, 30, 70); // 30kg - 70kg
            $age = $this->faker->numberBetween(1, 2); // 1 - 2 tahun
            $price = $this->faker->randomFloat(2, 2500000, 6000000); // 2.5jt - 6jt
            $maxParticipants = 1; // 1 kambing/domba untuk 1 orang
        }

        return [
            'animal_type' => $animalType,
            'name' => $name,
            'weight' => $weight,
            'age' => $age,
            'price' => $price,
            'photo' => null, // Dikosongkan, diisi lewat upload dashboard admin
            'max_participants' => $maxParticipants,
            'status' => $status,
        ];
    }
}
