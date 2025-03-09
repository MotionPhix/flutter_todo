<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
  Route::post('/{type}/{id}/notes', [NoteController::class, 'store'])->name('notes.store');
  Route::put('/notes/{note:uuid}', [NoteController::class, 'update'])->name('notes.update');
  Route::delete('/notes/{note:uuid}', [NoteController::class, 'destroy'])->name('notes.destroy');
});
