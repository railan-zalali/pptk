<?php

namespace Database\Factories;

use App\Models\Garden;
use App\Models\ProductionRealization;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionRealizationFactory extends Factory
{
    protected $model = ProductionRealization::class;

    public function definition(): array
    {
        $area       = $this->faker->randomFloat(2, 50, 800);
        $production = $area * $this->faker->randomFloat(2, 800, 2000); // Kg

        return [
            'kebun_id'               => Garden::factory(),
            'month'                  => $this->faker->numberBetween(1, 12),
            'year'                   => $this->faker->numberBetween(2022, 2026),
            'active_picking_area_ha' => $area,
            'wet_production_kg'      => $production,
            'capacity_per_ha'        => $this->faker->randomFloat(2, 30, 80),
            'avg_capacity'           => $this->faker->randomFloat(2, 20, 60),
            'estimated_production'   => $production * $this->faker->randomFloat(2, 0.9, 1.1),
            'quality_score'          => $this->faker->randomFloat(2, 6.0, 9.5),
            'assumption_note'        => $this->faker->optional()->sentence,
        ];
    }

    /**
     * State untuk tahun tertentu.
     */
    public function forYear(int $year): static
    {
        return $this->state(fn () => ['year' => $year]);
    }

    /**
     * State untuk bulan tertentu.
     */
    public function forMonth(int $month): static
    {
        return $this->state(fn () => ['month' => $month]);
    }
}
