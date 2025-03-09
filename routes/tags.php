<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
  Route::prefix('tags')->name('tags.')->group(function () {

    Route::get(
      '/',
      [TagController::class, 'index']
    )->name('index');

    Route::get(
      '/c/new-label',
      [TagController::class, 'create']
    )->name('create');

    Route::post(
      '/',
      [TagController::class, 'store']
    )->name('store');

    Route::get(
      '/cloud',
      [TagController::class, 'cloud']
    )->name('cloud');

    Route::delete(
      '/bulk',
      [TagController::class, 'bulkDelete']
    )->name('tags.bulk-delete');

    Route::get(
      '/{type}/{slug}',
      [TagController::class, 'show']
    )->name('show');

  });
});
