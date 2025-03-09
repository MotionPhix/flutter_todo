<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
  public function tasks(): MorphToMany
  {
    return $this->morphedByMany(Task::class, 'taggable');
  }

  public function projects(): MorphToMany
  {
    return $this->morphedByMany(Project::class, 'taggable');
  }

  public function notes(): MorphToMany
  {
    return $this->morphedByMany(Note::class, 'taggable');
  }
}
