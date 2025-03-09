<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    // In DatabaseSeeder.php
    $demoUser = User::factory()
      ->withAvatar()
      ->darkTheme()
      ->withAllNotifications()
      ->boardView()
      ->create([
        'name' => 'Demo User',
        'email' => 'demo@example.com',
        'password' => bcrypt('password'),
      ]);

    // Create some additional users with different preferences
    $users = User::factory(5)
      ->withAvatar()
      ->sequence(
        ['preferences' => ['theme' => 'light', 'display_mode' => 'list']],
        ['preferences' => ['theme' => 'dark', 'display_mode' => 'board']],
        ['preferences' => ['theme' => 'system', 'display_mode' => 'list']],
      )
      ->create();

    $allUsers = $users->push($demoUser);

    // Create projects
    $projects = Project::factory(3)
      ->active()
      ->sequence(
        ['name' => 'Marketing Campaign'],
        ['name' => 'Website Redesign'],
        ['name' => 'Mobile App'],
      )
      ->create([
        'owner_id' => $demoUser->id,
      ]);

    // Add members to projects
    $projects->each(function ($project) use ($allUsers) {
      // Add 2-4 random members to each project
      $project->members()->attach(
        $allUsers->random(rand(2, 4))->pluck('id')->toArray(),
        ['role' => 'member']
      );
    });

    // Create tasks for each project
    $projects->each(function ($project) use ($allUsers) {
      // Create 5-8 tasks per project
      Task::factory(rand(5, 8))
        ->sequence(fn($sequence) => [
          'project_id' => $project->id,
          'assigned_to' => $allUsers->random()->id,
          'created_by' => $allUsers->random()->id,
        ])
        ->create();
    });

    // Add comments to tasks
    Task::all()->each(function ($task) use ($allUsers) {
      // Add 0-5 comments per task
      TaskComment::factory(rand(0, 5))
        ->sequence(fn($sequence) => [
          'task_id' => $task->id,
          'user_id' => $allUsers->random()->id,
        ])
        ->create();
    });
  }
}
