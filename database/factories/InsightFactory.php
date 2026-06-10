<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\Insight;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsightFactory extends Factory
{
    protected $model = Insight::class;

    public function definition(): array
    {
        return [
            'garden_id'    => Garden::factory(),
            'title'        => $this->faker->sentence(4),
            'description'  => $this->faker->paragraph,
            'insight_type' => $this->faker->randomElement(['productivity', 'quality', 'strategic']),
            'message'      => $this->faker->sentence,
            'alert_level'  => $this->faker->randomElement(['low', 'medium', 'high']),
            'recommendations' => [
                $this->faker->sentence,
                $this->faker->sentence,
            ],
            'generated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function highAlert(): static
    {
        return $this->state(fn () => ['alert_level' => 'high']);
    }
}
