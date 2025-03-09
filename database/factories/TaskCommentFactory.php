<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskComment>
 */
class TaskCommentFactory extends Factory
{
  public function definition(): array
  {
    return [
      'task_id' => Task::factory(),
      'user_id' => User::factory(),
      'comment' => fake()->paragraph(),
      'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
      'updated_at' => function (array $attributes) {
        return fake()->dateTimeBetween($attributes['created_at'], 'now');
      },
    ];
  }
}
