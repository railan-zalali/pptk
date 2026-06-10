<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        return [
            'program_name' => 'Program ' . $this->faker->words(2, true),
            'description'  => $this->faker->optional()->paragraph,
            'year'         => $this->faker->numberBetween(2022, 2026),
            'program_type' => $this->faker->randomElement(['Produksi', 'Kualitas', 'Infrastruktur', 'SDM']),
            'status'       => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => false]);
    }
}
