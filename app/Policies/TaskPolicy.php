<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return true; // All authenticated users can view tasks
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Task $task): bool
  {
    return $task->project->owner_id === $user->id ||
      $task->project->members()->where('user_id', $user->id)->exists();
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return true; // Creation permission is checked at the project level
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Task $task): bool
  {
    return $task->created_by === $user->id ||
      $task->assigned_to === $user->id ||
      $task->project->owner_id === $user->id;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Task $task): bool
  {
    return $task->created_by === $user->id ||
      $task->project->owner_id === $user->id;
  }

  /**
   * Determine whether the user can update the status of the task.
   */
  public function updateStatus(User $user, Task $task): bool
  {
    return $task->created_by === $user->id ||
      $task->assigned_to === $user->id ||
      $task->project->owner_id === $user->id;
  }

  /**
   * Determine whether the user can add comments to the task.
   */
  public function addComment(User $user, Task $task): bool
  {
    return $task->project->owner_id === $user->id ||
      $task->project->members()->where('user_id', $user->id)->exists();
  }
}
