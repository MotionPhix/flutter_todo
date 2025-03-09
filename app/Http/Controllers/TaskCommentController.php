<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
  public function store(Request $request, Task $task): RedirectResponse
  {
    $validated = $request->validate([
      'comment' => 'required|string'
    ]);

    $task->comments()->create([
      ...$validated,
      'user_id' => $request->user()->id,
    ]);

    return back()->with('success', 'Comment added successfully.');
  }

  public function destroy(TaskComment $comment): RedirectResponse
  {
    $comment->delete();

    return back()->with('success', 'Comment deleted successfully.');
  }
}
