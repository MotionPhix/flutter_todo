<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
  public function viewAny(User $user): bool
  {
    return true;
  }

  public function view(User $user, Note $note): bool
  {
    return $note->visibility === 'public' ||
      $note->user_id === $user->id ||
      ($note->visibility === 'shared' && $note->user_id === $user->id);
  }

  public function create(User $user, $notable): bool
  {
    if ($notable instanceof \App\Models\Task) {
      return $notable->project->members()->where('user_id', $user->id)->exists();
    }

    if ($notable instanceof \App\Models\Project) {
      return $notable->members()->where('user_id', $user->id)->exists();
    }

    return false;
  }

  public function update(User $user, Note $note): bool
  {
    return $note->user_id === $user->id;
  }

  public function delete(User $user, Note $note): bool
  {
    return $note->user_id === $user->id;
  }
}
