<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\BoardGameController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/menu', [FoodItemController::class, 'publicIndex'])->name('food-items.public');
Route::get('/games', [BoardGameController::class, 'publicIndex'])->name('board-games.public');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        \Illuminate\Support\Facades\Artisan::call('orders:cancel-expired');

        $stats = [];
        if (Gate::allows('is-admin')) {
            $stats['pending_orders'] = \App\Models\Order::where('order_status', 'pending')->count();
            $stats['completed_orders'] = \App\Models\Order::where('order_status', 'completed')->count();
            $stats['total_orders'] = \App\Models\Order::count();

            $pendingTakeaways = \App\Models\Order::where('order_status', 'pending')
                ->where('order_type', 'takeaway')
                ->get();
        } else {
            $stats['my_orders'] = \App\Models\Order::where('user_id', Auth::id())->count();
            $stats['my_pending'] = \App\Models\Order::where('user_id', Auth::id())->where('order_status', 'pending')->count();

            $pendingTakeaways = \App\Models\Order::where('user_id', Auth::id())
                ->where('order_status', 'pending')
                ->where('order_type', 'takeaway')
                ->get();
        }
        return view('dashboard', compact('stats', 'pendingTakeaways'));
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

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Orders
    Route::post('/orders/manual', [OrderController::class, 'storeManual'])->name('orders.storeManual');
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'store', 'create']);
    Route::post('/orders/{order}/mark-paid', [OrderController::class, 'markPaid'])->name('orders.mark-paid');
    Route::post('/orders/{order}/mark-completed', [OrderController::class, 'markCompleted'])->name('orders.mark-completed');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Wishlist
    Route::resource('wishlists', WishlistController::class)->only(['index', 'store', 'destroy']);
});

require __DIR__.'/auth.php';
