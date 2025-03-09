<?php

namespace App\Policies;

use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskCommentPolicy
{
  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, TaskComment $comment): bool
  {
    return $comment->user_id === $user->id ||
      $comment->task->project->owner_id === $user->id;
  }
}
