<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
  public function index(Request $request): Response
  {
    $tasks = Task::query()
      ->with(['project', 'assignee', 'creator'])
      ->when($request->input('filter'), function ($query, $filter) {
        return match($filter) {
          'today' => $query->whereDate('due_date', today()),
          'overdue' => $query->where('due_date', '<', today())
            ->where('status', '!=', 'done'),
          'assigned' => $query->where('assigned_to', auth()->id()),
          'created' => $query->where('created_by', auth()->id()),
          default => $query
        };
      })
      ->when($request->input('status'), function ($query, $status) {
        return $query->where('status', $status);
      })
      ->when($request->input('priority'), function ($query, $priority) {
        return $query->where('priority', $priority);
      })
      ->orderBy('due_date', 'asc')
      ->paginate(20);

    return Inertia::render('Tasks/Index', [
      'tasks' => $tasks,
      'filters' => $request->only(['filter', 'status', 'priority'])
    ]);
  }

  public function store(Request $request, Project $project): RedirectResponse
  {
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|in:todo,in_progress,review,done',
      'priority' => 'required|in:low,medium,high',
      'assigned_to' => 'nullable|exists:users,id',
      'due_date' => 'nullable|date',
    ]);

    $project->tasks()->create([
      ...$validated,
      'created_by' => $request->user()->id,
    ]);

    return back()->with('success', 'Task created successfully.');
  }

  public function update(Request $request, Task $task): RedirectResponse
  {
    $validated = $request->validate([
      'title' => 'sometimes|required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'sometimes|required|in:todo,in_progress,review,done',
      'priority' => 'sometimes|required|in:low,medium,high',
      'assigned_to' => 'nullable|exists:users,id',
      'due_date' => 'nullable|date',
    ]);

    $task->update($validated);

    return back()->with('success', 'Task updated successfully.');
  }

  public function destroy(Task $task): RedirectResponse
  {
    $task->delete();

    return back()->with('success', 'Task deleted successfully.');
  }

  public function updateStatus(Request $request, Task $task): RedirectResponse
  {
    $validated = $request->validate([
      'status' => 'required|in:todo,in_progress,review,done',
    ]);

    $task->update($validated);

    return back()->with('success', 'Task status updated.');
  }
}
