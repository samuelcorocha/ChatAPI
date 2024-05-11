<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Messages>
 */
class MessagesFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    return [
      'message' => fake()->sentence,
      'room_id' => fake()->numberBetween(1, 20),
      'user_id' => fake()->numberBetween(0, 10) < 8 ? fake()->numberBetween(1, 2) : fake()->numberBetween(3, 22)
    ];
  }
}
