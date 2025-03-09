<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => fake()->name(),
      'email' => fake()->unique()->safeEmail(),
      'email_verified_at' => now(),
      'password' => static::$password ??= Hash::make('password'),
      'remember_token' => Str::random(10),
      'timezone' => fake()->randomElement([
        'UTC',
        'America/New_York',
        'Europe/London',
        'Asia/Tokyo',
        'Africa/Johannesburg',
        'Australia/Sydney'
      ]),
      'preferences' => [
        'theme' => fake()->randomElement(['light', 'dark', 'system']),
        'notification_email' => fake()->boolean(),
        'notification_web' => fake()->boolean(),
        'task_reminder' => fake()->boolean(),
        'daily_digest' => fake()->boolean(),
        'display_mode' => fake()->randomElement(['list', 'board']),
        'task_sort' => fake()->randomElement(['due_date', 'priority', 'created_at']),
        'task_filter' => fake()->randomElement(['all', 'assigned', 'created']),
      ],
    ];
  }

  public function unverified(): static
  {
    return $this->state(fn (array $attributes) => [
      'email_verified_at' => null,
    ]);
  }

  public function withAvatar(): static
  {
    return $this->afterCreating(function ($user) {
      // Add a random avatar from UI Avatars as a placeholder
      $user->addMediaFromUrl("https://ui-avatars.com/api/?name=" . urlencode($user->name))
        ->toMediaCollection('avatar');
    });
  }

  public function darkTheme(): static
  {
    return $this->state(fn (array $attributes) => [
      'preferences' => array_merge(
        $attributes['preferences'] ?? [],
        ['theme' => 'dark']
      ),
    ]);
  }

  public function lightTheme(): static
  {
    return $this->state(fn (array $attributes) => [
      'preferences' => array_merge(
        $attributes['preferences'] ?? [],
        ['theme' => 'light']
      ),
    ]);
  }

  public function withAllNotifications(): static
  {
    return $this->state(fn (array $attributes) => [
      'preferences' => array_merge(
        $attributes['preferences'] ?? [],
        [
          'notification_email' => true,
          'notification_web' => true,
          'task_reminder' => true,
          'daily_digest' => true,
        ]
      ),
    ]);
  }

  public function boardView(): static
  {
    return $this->state(fn (array $attributes) => [
      'preferences' => array_merge(
        $attributes['preferences'] ?? [],
        ['display_mode' => 'board']
      ),
    ]);
  }

  public function listView(): static
  {
    return $this->state(fn (array $attributes) => [
      'preferences' => array_merge(
        $attributes['preferences'] ?? [],
        ['display_mode' => 'list']
      ),
    ]);
  }
}
