<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition(): array
    {
        return [
            'garden_id'          => Garden::factory(),
            'title'              => 'Kunjungan ' . $this->faker->words(3, true),
            'visit_date'         => $this->faker->dateTimeBetween('-2 years', 'now'),
            'duration'           => $this->faker->numberBetween(2, 8),
            'participants_count' => $this->faker->numberBetween(2, 20),
            'participants_list'  => $this->faker->optional()->name . ', ' . $this->faker->name,
            'visitor_name'       => $this->faker->name,
            'description'        => $this->faker->paragraph,
            'objectives'         => $this->faker->optional()->sentence,
            'findings'           => $this->faker->optional()->paragraph,
            'recommendations'    => $this->faker->optional()->sentence,
            'purpose'            => $this->faker->sentence,
            'rating'             => $this->faker->numberBetween(1, 5),
            'status'             => $this->faker->randomElement(['scheduled', 'completed', 'cancelled']),
        ];
    }

    /**
     * State untuk kunjungan yang sudah selesai.
     */
    public function completed(): static
    {
        return $this->state(fn () => [
            'status'          => 'completed',
            'findings'        => $this->faker->paragraph,
            'recommendations' => $this->faker->sentence,
        ]);
    }
}
