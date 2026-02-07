<?php

namespace Database\Factories;

use App\Models\District;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'district_id' => District::factory(),
            'name' => $this->faker->word() . ' قطاع',
        ];
    }
}
