<?php

namespace App\Http\Controllers;

use App\Enums\TagType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class TagController extends Controller
{
  public function index()
  {
    $tagsByType = collect(TagType::cases())->mapWithKeys(fn ($type) => [
      $type->value => Tag::getWithType($type->value)
        ->map(fn ($tag) => [
          'id' => $tag->id,
          'name' => $tag->name,
          'slug' => $tag->slug,
          'count' => $tag->tasks_count + $tag->projects_count + $tag->notes_count,
        ])
    ]);

    return Inertia::render('Tags/Index', [
      'tagsByType' => $tagsByType
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required|string|in:' . implode(',', array_column(TagType::cases(), 'value')),
      'color' => 'required|string|size:7|starts_with:#',
    ]);

    $tag = Tag::findOrCreate($validated['name'], $validated['type']);
    $tag->color = $validated['color'];
    $tag->save();

    return back()->with('success', 'Tag created successfully.');
  }

  public function show(string $type, string $slug)
  {
    $tag = Tag::findFromSlug($slug);

    abort_if($tag->type !== $type, 404);

    $items = collect([
      'tasks' => \App\Models\Task::withAnyTags([$tag->name])
        ->with(['project', 'user'])
        ->withCount('subtasks')
        ->latest()
        ->get(),
      'projects' => \App\Models\Project::withAnyTags([$tag->name])
        ->with(['owner'])
        ->withCount('tasks')
        ->latest()
        ->get(),
      'notes' => \App\Models\Note::withAnyTags([$tag->name])
        ->with(['user', 'notable'])
        ->latest()
        ->get(),
    ])->flatMap(function ($items, $type) {
      return $items->map(fn ($item) => [
        'id' => $item->id,
        'type' => str($type)->singular()->title(),
        'title' => $item->title ?? $item->name,
        'description' => $item->description ?? $item->content,
        'count' => $item->subtasks_count ?? $item->tasks_count ?? null,
        'created_at' => $item->created_at,
        'user' => $type === 'projects' ? $item->owner : $item->user,
        'route' => route("$type.show", $item->id),
      ]);
    })->sortByDesc('created_at')->values();

    return Inertia::render('Tags/Show', [
      'tag' => [
        'id' => $tag->id,
        'name' => $tag->name,
        'type' => $tag->type,
        'color' => $tag->color,
        'slug' => $tag->slug,
      ],
      'items' => $items,
    ]);
  }

  public function cloud()
  {
    $tags = Tag::withCount(['tasks', 'projects', 'notes'])
      ->having('tasks_count', '>', 0)
      ->orHaving('projects_count', '>', 0)
      ->orHaving('notes_count', '>', 0)
      ->get()
      ->map(fn ($tag) => [
        'id' => $tag->id,
        'name' => $tag->name,
        'type' => $tag->type,
        'color' => $tag->color,
        'slug' => $tag->slug,
        'weight' => $tag->tasks_count + $tag->projects_count + $tag->notes_count,
      ]);

    return Inertia::render('Tags/Cloud', [
      'tags' => $tags
    ]);
  }
}
