<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

// Halaman utama
Route::get('/', function () {
    return view('Main');
});

// Menu (CRUD)
Route::resource('menu', MenuController::class);
// Route::get('/menu', [MenuController::class, 'index'])->name('menu');
// Route::get('/create', [MenuController::class, 'create'])->name('create');
// Route::post('/menu', [MenuController::class, 'store']);
// Route::get('/delete', [MenuController::class, 'deleteView']);

// Login & Register
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Admin Dashboard
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});

// Dashboard untuk user login
// Authentication Routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
// Route::get('/register', [App\Http\Controllers\RegisteredUserController::class, 'create'])->name('register');
// Route::post('/register', [App\Http\Controllers\RegisteredUserController::class, 'store'])->name('register.post');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Order dan Cart
Route::get('/order', [OrderController::class, 'order']);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'showCart']);
Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart']);

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout']);
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// Profile (hanya bisa diakses jika login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
