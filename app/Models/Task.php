<?php

namespace App\Models;

use App\Traits\HasBootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Task extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, InteractsWithMedia, HasBootUuid, HasTags;

  protected $fillable = [
    'title',
    'description',
    'status',
    'priority',
    'project_id',
    'assigned_to',
    'created_by',
    'due_date',
  ];

  protected $casts = [
    'status' => 'string',
    'priority' => 'string',
    'due_date' => 'datetime',
  ];

  // Relationships
  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class);
  }

  public function assignee(): BelongsTo
  {
    return $this->belongsTo(User::class, 'assigned_to');
  }

  public function creator(): BelongsTo
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function comments(): HasMany
  {
    return $this->hasMany(TaskComment::class);
  }

  public function notes()
  {
    return $this->morphMany(Note::class, 'notable');
  }

  // Helper methods
  public function isOverdue(): bool
  {
    return $this->due_date && $this->due_date->isPast();
  }

  public function isCompleted(): bool
  {
    return $this->status === 'done';
  }
}
