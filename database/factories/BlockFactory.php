<?php

namespace Database\Factories;

use App\Models\Afdeling;
use App\Models\Block;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockFactory extends Factory
{
    protected $model = Block::class;

    public function definition(): array
    {
        return [
            'afdeling_id'   => Afdeling::factory(),
            'name'          => 'Blok ' . strtoupper($this->faker->unique()->bothify('??##')),
            'code'          => $this->faker->optional()->bothify('BL-###'),
            'area_ha'       => $this->faker->randomFloat(2, 1, 50),
            'population'    => $this->faker->numberBetween(500, 5000),
            'plant_type'    => $this->faker->randomElement(['TRI 2025', 'PS 1', 'Assamica', 'Sinensis']),
            'planting_year' => $this->faker->numberBetween(1970, 2020),
            'initial_class' => $this->faker->randomElement(['A', 'B', 'C', null]),
            'topography'    => $this->faker->randomElement(['Datar', 'Berbukit', 'Curam', null]),
        ];
    }
}
