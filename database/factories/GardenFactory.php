<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class GardenFactory extends Factory
{
    protected $model = Garden::class;

    public function definition(): array
    {
        return [
            'regional_id'       => Region::factory(),
            'kebun_name'        => 'Kebun ' . $this->faker->unique()->word() . ' ' . $this->faker->numberBetween(1, 99),
            'luas_total_ha'     => $this->faker->randomFloat(2, 100, 5000),
            'kebun_type'        => $this->faker->randomElement(['Model', 'Pengembangan']),
            'agro_climate_note' => $this->faker->optional()->sentence,
            'location'          => $this->faker->city,
            'photo_path'        => null,
            'description'       => $this->faker->optional()->paragraph,
            'established_at'    => $this->faker->optional()->dateTimeBetween('-50 years', '-1 year'),
        ];
    }

    /**
     * State untuk Kebun Model saja.
     */
    public function model(): static
    {
        return $this->state(fn () => ['kebun_type' => 'Model']);
    }
}
