<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;

// ==========================
// Halaman Utama
// ==========================
Route::get('/', function () {
    return view('Main');
})->name('home');

// ==========================
// Menu (CRUD)
// ==========================
Route::resource('menu', MenuController::class);

// ==========================
// Login & Register
// ==========================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

// ==========================
// Logout
// ==========================
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// ==========================
// Admin Dashboard (hanya untuk admin)
// ==========================
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// ==========================
// Dashboard untuk user login
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// ==========================
// Order & Cart
// ==========================
Route::get('/order', [OrderController::class, 'order']);
Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'showCart']);
Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart']);

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/process-checkout', [CartController::class, 'processCheckout']);
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// ==========================
// Profil (hanya untuk user yang login)
// ==========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================
// Route Otomatis dari Laravel Breeze / Jetstream (jika digunakan)
// ==========================
// require __DIR__.'/auth.php';
