<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PayementIntent;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/locations/{location}/upvote', [VoteController::class, 'upvote'])->name('locations.upvote');
    Route::middleware('admin')->group(function () {
        Route::resource('films', FilmController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('locations', LocationController::class)->except(['index', 'show', 'create', 'store']);
    });
    Route::resource('films', FilmController::class)->only(['index', 'show']);
    Route::get('/locations/paiement', PayementIntent::class)->name('locations.paiement');
    Route::post('/locations/paiement/confirm', [PayementIntent::class, 'confirm'])->name('locations.paiement.confirm');
    Route::resource('locations', LocationController::class)->only(['index', 'show', 'create', 'store']);
});

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('auth.oauth.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('auth.oauth.callback');

require __DIR__.'/auth.php';
