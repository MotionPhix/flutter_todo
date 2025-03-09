<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return true; // All authenticated users can view projects
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Project $project): bool
  {
    return $project->owner_id === $user->id ||
      $project->members()->where('user_id', $user->id)->exists();
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return true; // All authenticated users can create projects
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Project $project): bool
  {
    return $project->owner_id === $user->id;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Project $project): bool
  {
    return $project->owner_id === $user->id;
  }

  /**
   * Determine whether the user can manage members.
   */
  public function manageMembers(User $user, Project $project): bool
  {
    return $project->owner_id === $user->id;
  }

  /**
   * Determine whether the user can add tasks to the project.
   */
  public function addTasks(User $user, Project $project): bool
  {
    return $project->owner_id === $user->id ||
      $project->members()->where('user_id', $user->id)->exists();
  }
}
