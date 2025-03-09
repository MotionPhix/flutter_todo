<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

  // Projects
  Route::prefix('projects')->name('projects.')->group(function () {

    Route::post(
      'a/members/{project:uuid}',
      [ProjectController::class, 'addMember']
    )->name('members.add');

    Route::delete(
      'r/members/{project}/{user}',
      [ProjectController::class, 'removeMember']
    )->name('projects.members.remove');

  });

});
