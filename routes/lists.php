<?php

use App\Http\Controllers\TaskListController;
use App\Models\TaskList;
use Illuminate\Support\Facades\Route;
use Spatie\Tags\Tag;

Route::middleware(['auth'])->group(function () {
  // Modal routes
  Route::get('lists/create', function () {
    return inertia('lists/Create');
  })->name('lists.create');

  Route::get('lists/{list}/edit', function (TaskList $list) {
    return inertia('lists/Edit', [
      'list' => $list
    ]);
  })->name('lists.edit');

  Route::get('lists/s/{list}', function (TaskList $list) {
    return inertia('lists/Show', [
      'list' => $list
    ]);
  })->name('lists.show');

  Route::get('labels/create', function () {
    return inertia('labels/Create');
  })->name('labels.create');

  Route::get('labels/{label}/edit', function (Tag $label) {
    return inertia('labels/Edit', [
      'label' => $label
    ]);
  })->name('labels.edit');

  Route::get('labels/s/{label}', function (Tag $label) {
    return inertia('labels/Show', [
      'label' => $label
    ]);
  })->name('labels.show');

  // List routes
  Route::post('lists/reorder', [TaskListController::class, 'reorder'])->name('lists.reorder');
  Route::delete('lists/bulk', [TaskListController::class, 'bulkDelete'])->name('lists.bulk-delete');
});
