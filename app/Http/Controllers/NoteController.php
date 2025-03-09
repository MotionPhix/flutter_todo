<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NoteController extends Controller
{
  use AuthorizesRequests;

  public function index(): Response
  {
    $notes = Note::query()
      ->with('user')
      ->where(function ($query) {
        $query->where('visibility', 'public')
          ->orWhere(function ($q) {
            $q->where('visibility', 'shared')
              ->where('user_id', auth()->id());
          });
      })
      ->latest()
      ->paginate();

    return Inertia::render('Notes/Index', [
      'notes' => $notes
    ]);
  }

  public function store(Request $request, string $type, int $id)
  {
    $notable = match($type) {
      'task' => Task::findOrFail($id),
      'project' => Project::findOrFail($id),
      default => abort(404)
    };

    $this->authorize('create', [Note::class, $notable]);

    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'content' => 'required|string',
      'visibility' => 'required|in:public,private,shared',
    ]);

    $note = $notable->notes()->create([
      ...$validated,
      'user_id' => auth()->id(),
    ]);

    return back()->with('success', 'Note created successfully.');
  }

  public function update(Request $request, Note $note)
  {
    $this->authorize('update', $note);

    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'content' => 'required|string',
      'visibility' => 'required|in:public,private,shared',
    ]);

    $note->update($validated);

    return back()->with('success', 'Note updated successfully.');
  }

  public function destroy(Note $note)
  {
    $this->authorize('delete', $note);

    $note->delete();

    return back()->with('success', 'Note deleted successfully.');
  }
}
