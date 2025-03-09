<?php

namespace App\Http\Middleware;

use App\Models\Tag;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
  /**
   * The root template that's loaded on the first page visit.
   *
   * @see https://inertiajs.com/server-side-setup#root-template
   *
   * @var string
   */
  protected $rootView = 'app';

  /**
   * Determines the current asset version.
   *
   * @see https://inertiajs.com/asset-versioning
   */
  public function version(Request $request): ?string
  {
    return parent::version($request);
  }

  /**
   * Define the props that are shared by default.
   *
   * @see https://inertiajs.com/shared-data
   *
   * @return array<string, mixed>
   */
  public function share(Request $request): array
  {
    [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

    return [
      ...parent::share($request),
      'name' => config('app.name'),
      'quote' => ['message' => trim($message), 'author' => trim($author)],
      'auth' => [
        'user' => $request->user(),
      ],
      'lists' => fn () => auth()->check() ? \App\Models\TaskList::withCount('tasks')
        ->where('user_id', auth()->id())
        ->get() : [],

      'labels' => fn () => auth()->check() ? Tag::withCount(['tasks', 'projects', 'notes'])
        ->get()
        ->map(fn ($tag) => [
          'id' => $tag->id,
          'name' => $tag->name,
          'type' => $tag->type,
          'color' => $tag->color,
          'slug' => $tag->slug,
          'count' => $tag->tasks_count + $tag->projects_count + $tag->notes_count,
        ]) : [],
      'ziggy' => [
        ...(new Ziggy)->toArray(),
        'location' => $request->url(),
      ],
    ];
  }
}
