<?php

namespace App\Models;

use App\Enums\NoteVisibility;
use App\Traits\HasBootUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Tags\HasTags;

class Note extends Model
{
  use HasFactory, HasBootUuid, HasTags;

  protected $fillable = [
    'title',
    'content',
    'visibility',
    'user_id',
    'notable_type',
    'notable_id',
  ];

  protected $casts = [
    'visibility' => NoteVisibility::class,
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function notable(): MorphTo
  {
    return $this->morphTo();
  }
}
