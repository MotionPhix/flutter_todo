<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

  Route::post(
    'search',
    [\App\Http\Controllers\SearchController::class, 'search']
  )->name('api.search');

});
