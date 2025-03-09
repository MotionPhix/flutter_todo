<?php

use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

  Route::prefix('tasks')->name('tasks.')->group(function () {

    Route::post(
      'projects/{project:uuid}',
      [TaskController::class, 'store']
    )->name('project.store');

    Route::post(
      'status/{task:uuid}',
      [TaskController::class, 'updateStatus']
    )->name('update-status');

    // Task Comments
    Route::post(
      'comments/{task:uuid}',
      [TaskCommentController::class, 'store']
    )->name('comments.store');

    Route::delete(
      'comments/{comment}',
      [TaskCommentController::class, 'destroy']
    )->name('tasks.comments.destroy');

  });

});
