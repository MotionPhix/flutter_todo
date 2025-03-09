<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskListController extends Controller
{
  use AuthorizesRequests;

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
    ]);

    $list = $request->user()->taskLists()->create($validated);

    return back()->with('success', 'List created successfully.');
  }

  public function update(Request $request, TaskList $list)
  {
    $this->authorize('update', $list);

    $validated = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'position' => ['sometimes', 'integer'],
    ]);

    $list->update($validated);

    return back()->with('success', 'List updated successfully.');
  }

  public function destroy(TaskList $list)
  {
    $this->authorize('delete', $list);

    // Move tasks to "No List" (null list_id)
    $list->tasks()->update(['list_id' => null]);

    $list->delete();

    return back()->with('success', 'List deleted successfully.');
  }

  public function reorder(Request $request)
  {
    $validated = $request->validate([
      'lists' => ['required', 'array'],
      'lists.*' => ['required', 'integer', Rule::exists('task_lists', 'id')],
    ]);

    foreach ($validated['lists'] as $position => $listId) {
      TaskList::where('id', $listId)
        ->where('user_id', $request->user()->id)
        ->update(['position' => $position]);
    }

    return response()->json(['message' => 'Lists reordered successfully']);
  }

  public function bulkDelete(Request $request)
  {
    $validated = $request->validate([
      'ids' => 'required|array',
      'ids.*' => 'required|integer|exists:task_lists,id',
    ]);

    $lists = TaskList::whereIn('id', $validated['ids'])
      ->where('user_id', $request->user()->id)
      ->get();

    foreach ($lists as $list) {
      $list->tasks()->update(['list_id' => null]);
      $list->delete();
    }

    return back()->with('success', 'Selected lists deleted successfully.');
  }
}
