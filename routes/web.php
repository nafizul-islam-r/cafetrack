<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\BoardGameController;
use App\Http\Controllers\AssignmentController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/menu', [FoodItemController::class, 'publicIndex'])->name('food-items.public');
Route::get('/games', [BoardGameController::class, 'publicIndex'])->name('board-games.public');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('food-items', FoodItemController::class);
    Route::resource('board-games', BoardGameController::class);
    
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
