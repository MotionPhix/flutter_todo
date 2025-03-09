<?php

use App\Http\Controllers\TaskListController;
use App\Models\TaskList;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

  Route::prefix('lists')->name('lists.')->group(function () {

    // Modal routes
    Route::get(
      'c/new-list',
      [TaskListController::class, 'create']
    )->name('create');

    Route::get(
      'e/{list}',
      [TaskListController::class, 'edit'],
    )->name('edit');

    Route::get('s/{list}', function (TaskList $list) {
      return Inertia('lists/Show', [
        'list' => $list
      ]);
    })->name('show');

    // List routes
    Route::post(
      'r/reorder',
      [TaskListController::class, 'reorder']
    )->name('reorder');

    Route::delete(
      'b/bulk',
      [TaskListController::class, 'bulkDelete']
    )->name('bulk-delete');

  });

});
