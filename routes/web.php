<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
Route::resource('menu', MenuController::class);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu');
Route::get('/create', [\App\Http\Controllers\MenuController::class, 'create'])->name('create');
Route::post('/menu', [\App\Http\Controllers\MenuController::class, 'store']);
Route::get('/delete', [\App\Http\Controllers\MenuController::class, 'deleteView']);
Route::get('/order', [\App\Http\Controllers\OrderController::class, 'order']);
Route::post('/add-to-cart', [\App\Http\Controllers\OrderController::class, 'addToCart']);   
Route::get('/checkout', [\App\Http\Controllers\OrderController::class, 'checkoutView']);
Route::post('/checkout', [\App\Http\Controllers\OrderController::class, 'checkout']);
Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout']);
Route::get('/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
Route::post('/process-checkout', [\App\Http\Controllers\CartController::class, 'processCheckout']);
Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');




Route::post('/add-to-cart', [\App\Http\Controllers\CartController::class, 'addToCart']);
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'showCart']);
Route::post('/remove-from-cart/{id}', [\App\Http\Controllers\CartController::class, 'removeFromCart']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

require __DIR__.'/auth.php';
