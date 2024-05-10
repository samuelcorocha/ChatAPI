<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
        'name' => fake()->words(3, true),
        'status' => fake()->numberBetween(0, 10) < 7 ? 0 : 1,
        'limit' => fake()->numberBetween(0, 10) < 8 ? null : fake()->numberBetween(5, 10)
      ];
    }
}
