<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\PerformanceTarget;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceTargetFactory extends Factory
{
    protected $model = PerformanceTarget::class;

    public function definition(): array
    {
        $min = $this->faker->randomFloat(2, 1000, 1500);

        return [
            'kebun_id'          => Garden::factory(),
            'year'              => $this->faker->numberBetween(2022, 2026),
            'target_protas_min' => $min,
            'target_protas_max' => $min + $this->faker->randomFloat(2, 100, 500),
            'note'              => $this->faker->optional()->sentence,
        ];
    }
}
