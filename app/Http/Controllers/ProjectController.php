<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
  public function index(): Response
  {
    $projects = Project::query()
      ->with(['owner', 'members'])
      ->withCount('tasks')
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    return Inertia::render('Projects/Index', [
      'projects' => $projects
    ]);
  }

  public function create(): Response
  {
    return Inertia::render('Projects/Create');
  }

  public function store(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
    ]);

    $project = $request->user()->ownedProjects()->create($validated);

    // Add creator as a member with owner role
    $project->members()->attach($request->user(), ['role' => 'owner']);

    return redirect()->route('projects.show', $project)
      ->with('success', 'Project created successfully.');
  }

  public function show(Project $project): Response
  {
    $project->load(['owner', 'members', 'tasks' => function ($query) {
      $query->with(['assignee', 'creator'])
        ->orderBy('created_at', 'desc');
    }]);

    return Inertia::render('Projects/Show', [
      'project' => $project
    ]);
  }

  public function edit(Project $project): Response
  {
    return Inertia::render('Projects/Edit', [
      'project' => $project->load(['owner', 'members'])
    ]);
  }

  public function update(Request $request, Project $project): RedirectResponse
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'status' => 'required|in:active,archived',
    ]);

    $project->update($validated);

    return redirect()->route('projects.show', $project)
      ->with('success', 'Project updated successfully.');
  }

  public function destroy(Project $project): RedirectResponse
  {
    $project->delete();

    return redirect()->route('projects.index')
      ->with('success', 'Project deleted successfully.');
  }

  public function addMember(Request $request, Project $project): RedirectResponse
  {
    $validated = $request->validate([
      'user_id' => 'required|exists:users,id',
      'role' => 'required|in:member',
    ]);

    $project->members()->attach($validated['user_id'], [
      'role' => $validated['role']
    ]);

    return back()->with('success', 'Member added successfully.');
  }

  public function removeMember(Project $project, $userId): RedirectResponse
  {
    $project->members()->detach($userId);

    return back()->with('success', 'Member removed successfully.');
  }
}
