<?php

namespace Database\Factories;

use App\Models\CommunityService;
use App\Models\Garden;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityServiceFactory extends Factory
{
    protected $model = CommunityService::class;

    public function definition(): array
    {
        $total     = $this->faker->randomFloat(2, 5_000_000, 100_000_000);
        $absorbed  = $total * $this->faker->randomFloat(2, 0, 1);
        $startDate = $this->faker->dateTimeBetween('-1 year', '+3 months');

        return [
            'garden_id'        => $this->faker->boolean(60) ? Garden::factory() : null,
            'activity_name'    => 'Kegiatan ' . $this->faker->words(3, true),
            'team_name'        => 'Tim ' . $this->faker->lastName,
            'total_budget'     => $total,
            'remaining_budget' => $total - $absorbed,
            'year'             => $this->faker->numberBetween(2022, 2026),
            'status'           => $this->faker->randomElement(['planned', 'ongoing', 'completed']),
            'start_date'       => $startDate,
            'end_date'         => $this->faker->optional()->dateTimeBetween($startDate, '+6 months'),
            'description'      => $this->faker->optional()->paragraph,
            'location'         => $this->faker->optional()->city,
        ];
    }
}
