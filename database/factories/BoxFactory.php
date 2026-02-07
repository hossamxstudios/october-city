<?php

namespace Database\Factories;

use App\Models\Rack;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'rack_id' => Rack::factory(),
            'name' => 'بوكس ' . $this->faker->numberBetween(1, 100),
        ];
    }
}
