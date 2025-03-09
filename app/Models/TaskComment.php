<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TaskComment extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, InteractsWithMedia;

  protected $fillable = [
    'task_id',
    'user_id',
    'comment',
  ];

  // Relationships
  public function task(): BelongsTo
  {
    return $this->belongsTo(Task::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
