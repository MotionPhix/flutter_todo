<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
  public function definition(): array
  {
    return [
      'title' => fake()->sentence(),
      'description' => fake()->paragraph(),
      'status' => fake()->randomElement(['todo', 'in_progress', 'review', 'done']),
      'priority' => fake()->randomElement(['low', 'medium', 'high']),
      'project_id' => Project::factory(),
      'assigned_to' => User::factory(),
      'created_by' => User::factory(),
      'due_date' => fake()->dateTimeBetween('now', '+2 months'),
      'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
      'updated_at' => function (array $attributes) {
        return fake()->dateTimeBetween($attributes['created_at'], 'now');
      },
    ];
  }

  public function todo(): self
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'todo',
    ]);
  }

  public function inProgress(): self
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'in_progress',
    ]);
  }

  public function inReview(): self
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'review',
    ]);
  }

  public function done(): self
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'done',
    ]);
  }

  public function highPriority(): self
  {
    return $this->state(fn (array $attributes) => [
      'priority' => 'high',
    ]);
  }
}
