<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'timezone',
    'preferences',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var list<string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'preferences' => 'array',
    ];
  }

  protected $appends = [
    'avatar',
  ];

  // Register media collections
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('avatar')
      ->singleFile()
      ->useFallbackUrl('https://ui-avatars.com/api/?name=' . urlencode($this->name))
      ->useFallbackPath(public_path('/images/default-avatar.png'));
  }

  // Custom conversion for avatar
  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb')
      ->width(100)
      ->height(100)
      ->sharpen(10);
  }

  // Avatar accessor
  public function getAvatarAttribute(): string
  {
    return $this->getFirstMediaUrl('avatar') ?: $this->getFirstMediaUrl('avatar', 'thumb');
  }

  // Relationships that will be needed for the task management system
  public function ownedProjects(): HasMany
  {
    return $this->hasMany(Project::class, 'owner_id');
  }

  public function projects(): BelongsToMany
  {
    return $this->belongsToMany(Project::class, 'project_members')
      ->withPivot('role')
      ->withTimestamps();
  }

  public function assignedTasks(): HasMany
  {
    return $this->hasMany(Task::class, 'assigned_to');
  }

  public function createdTasks(): HasMany
  {
    return $this->hasMany(Task::class, 'created_by');
  }

  public function taskComments(): HasMany
  {
    return $this->hasMany(TaskComment::class);
  }
}
