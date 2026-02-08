<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('states.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    /**
     * Profile Management Routes
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * State Management Routes
     */
    Route::get('/states', [StateController::class, 'index'])
        ->name('states.index');

    Route::get('/states/data', [StateController::class, 'data'])
        ->name('states.data');

    Route::post('/states/synchronize', [StateController::class, 'synchronize'])
        ->name('states.synchronize');

    /**
     * Municipality Routes
     */
    Route::get('/states/{estado}/municipalities',[StateController::class, 'municipalities'])
        ->name('states.municipalities');
});

require __DIR__.'/auth.php';
