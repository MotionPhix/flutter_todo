<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
  Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
  Route::get('/tags/cloud', [TagController::class, 'cloud'])->name('tags.cloud');
  Route::get('/tags/{type}/{slug}', [TagController::class, 'show'])->name('tags.show');
  Route::delete('tags/bulk', [TagController::class, 'bulkDelete'])->name('tags.bulk-delete');

  Route::get('labels/create', function () {
    return inertia('tags/Create');
  })->name('labels.create');
});
