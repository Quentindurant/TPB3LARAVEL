<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FilmLocationsController;
use App\Http\Controllers\Api\PublicFilmsController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::get('films', [PublicFilmsController::class, 'index']);
    Route::get('films/{film}/locations', [PublicFilmsController::class, 'locations']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');

Route::middleware('auth:api')
    ->get('films/{film}/locations', FilmLocationsController::class)
    ->name('api.films.locations');
