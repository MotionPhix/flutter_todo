<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
  public function definition(): array
  {
    return [
      'name' => fake()->words(3, true),
      'description' => fake()->paragraph(),
      'status' => fake()->randomElement(['active', 'archived']),
      'owner_id' => User::factory(),
      'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
      'updated_at' => function (array $attributes) {
        return fake()->dateTimeBetween($attributes['created_at'], 'now');
      },
    ];
  }

  public function active(): self
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'active',
    ]);
  }

  public function archived(): self
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'archived',
    ]);
  }
}
