<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Note;
use Spatie\Tags\Tag;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  public function search(Request $request)
  {
    $query = $request->input('query');

    return response()->json([
      'tasks' => Task::where('title', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->with(['user', 'project'])
        ->take(5)
        ->get()
        ->map(fn ($task) => [
          'id' => $task->id,
          'title' => $task->title,
          'project' => $task->project?->name,
        ]),

      'projects' => Project::where('name', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")
        ->with('owner')
        ->take(5)
        ->get()
        ->map(fn ($project) => [
          'id' => $project->id,
          'name' => $project->name,
          'owner' => $project->owner->name,
        ]),

      'notes' => Note::where('title', 'like', "%{$query}%")
        ->orWhere('content', 'like', "%{$query}%")
        ->with('user')
        ->take(5)
        ->get()
        ->map(fn ($note) => [
          'id' => $note->id,
          'title' => $note->title,
          'user' => $note->user->name,
        ]),

      'tags' => Tag::where('name', 'like', "%{$query}%")
        ->take(5)
        ->get()
        ->map(fn ($tag) => [
          'id' => $tag->id,
          'name' => $tag->name,
          'type' => $tag->type,
          'slug' => $tag->slug,
        ]),
    ]);
  }
}
