<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\Program;
use App\Models\StrategicAction;
use Illuminate\Database\Eloquent\Factories\Factory;

class StrategicActionFactory extends Factory
{
    protected $model = StrategicAction::class;

    public function definition(): array
    {
        return [
            'kebun_id'                => Garden::factory(),
            'program_id'              => null,
            'year'                    => $this->faker->numberBetween(2022, 2026),
            'realization_date'        => $this->faker->optional()->date(),
            'action_type'             => $this->faker->randomElement([
                'fertilizer_root', 'fertilizer_leaf', 'weed_control',
                'cultivator', 'picking', 'machine', 'opt',
            ]),
            'status'                  => $this->faker->randomElement(['planned', 'in_progress', 'completed']),
            'coverage_target_percent' => $this->faker->randomFloat(2, 50, 100),
            'realization_percent'     => $this->faker->randomFloat(2, 0, 100),
            'note'                    => $this->faker->optional()->sentence,
        ];
    }

    /**
     * State untuk fertilizer_root.
     */
    public function fertilizerRoot(): static
    {
        return $this->state(fn () => [
            'action_type'            => 'fertilizer_root',
            'dosis_n_kg_ha'          => $this->faker->randomFloat(2, 50, 200),
            'realized_dosis_n_kg_ha' => $this->faker->randomFloat(2, 40, 180),
            'n_protas_percent'       => $this->faker->randomFloat(2, 60, 100),
            'application_frequency'  => $this->faker->numberBetween(2, 6),
            'fertilizer_type'        => $this->faker->randomElement(['Urea', 'NPK', 'ZA', 'Pupuk Organik']),
        ]);
    }

    /**
     * State untuk machine.
     */
    public function machine(): static
    {
        return $this->state(fn () => [
            'action_type'    => 'machine',
            'total_machine'  => $this->faker->numberBetween(1, 20),
            'avg_machine_age' => $this->faker->randomFloat(2, 1, 15),
            'renewal_status' => $this->faker->randomElement(['Perlu Ganti', 'Masih Baik', 'Urgent']),
        ]);
    }

    /**
     * State untuk status completed.
     */
    public function completed(): static
    {
        return $this->state(fn () => [
            'status'             => 'completed',
            'realization_percent' => $this->faker->randomFloat(2, 80, 100),
            'realization_date'   => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }
}
