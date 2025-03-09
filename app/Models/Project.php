<?php

namespace App\Models;

use App\Traits\HasBootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, InteractsWithMedia, HasBootUuid;

  protected $fillable = [
    'name',
    'description',
    'status',
    'owner_id',
  ];

  protected $casts = [
    'status' => 'string',
  ];

  // Relationships
  public function owner(): BelongsTo
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function members(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'project_members')
      ->withPivot('role')
      ->withTimestamps();
  }

  public function tasks(): HasMany
  {
    return $this->hasMany(Task::class);
  }

  // Helper methods
  public function isOwner(User $user): bool
  {
    return $this->owner_id === $user->id;
  }

  public function isMember(User $user): bool
  {
    return $this->members()->where('user_id', $user->id)->exists();
  }
}
