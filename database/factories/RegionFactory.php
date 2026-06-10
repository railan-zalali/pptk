<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->city . ' Region';

        return [
            'regional_code' => Str::upper(Str::slug($name, '_')),
            'regional_name' => $name,
            'province'      => $this->faker->state,
            'coordinates'   => $this->faker->latitude(-8, -6) . ',' . $this->faker->longitude(105, 115),
            'photo_path'    => null,
        ];
    }
}
