<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/projects.php';
require __DIR__.'/tags.php';
require __DIR__.'/lists.php';
require __DIR__.'/tasks.php';
require __DIR__.'/notes.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
