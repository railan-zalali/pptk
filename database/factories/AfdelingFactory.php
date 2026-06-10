<?php

namespace Database\Factories;

use App\Models\Afdeling;
use App\Models\Garden;
use Illuminate\Database\Eloquent\Factories\Factory;

class AfdelingFactory extends Factory
{
    protected $model = Afdeling::class;

    public function definition(): array
    {
        $totalArea = $this->faker->randomFloat(2, 10, 500);

        return [
            'kebun_id'      => Garden::factory(),
            'name'          => 'Afdeling ' . $this->faker->unique()->randomLetter() . strtoupper($this->faker->randomLetter()),
            'total_area_ha' => $totalArea,
            'tm_area_ha'    => $this->faker->randomFloat(2, 5, $totalArea),
            'manager_name'  => $this->faker->optional()->name,
        ];
    }
}
