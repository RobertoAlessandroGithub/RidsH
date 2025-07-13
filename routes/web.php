<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController; // Pastikan UserController di-import

// ==========================
// RUTE PUBLIK & OTENTIKASI
// ==========================
Route::get('/', function () { return view('layouts.main'); })->name('home');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
Route::post('/logout', function () { Auth::logout(); return redirect('/login'); })->name('logout');

// ======================================================================
// GRUP ROUTE KHUSUS ADMIN
// ======================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Semua rute di bawah ini sekarang memiliki prefix /admin
    // Contoh: /admin/menu, /admin/categories, /admin/users
    Route::resource('menu', MenuController::class);
    Route::post('menu/{menu}/toggle-status', [MenuController::class, 'toggleActiveStatus'])->name('menu.toggle-status');
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class); // Untuk manajemen pengguna

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });
});

// ==========================
// RUTE UNTUK PELANGGAN (CUSTOMER)
// ==========================
Route::get('/maminko', [MenuController::class, 'maminkoIndex'])->name('maminko.index');
Route::get('/menu/{slug}', [MenuController::class, 'showDetail'])->name('menu.detail');

// --- BARIS INI YANG MENYEBABKAN MASALAH DAN SUDAH DIHAPUS ---
// Route::resource('menu', MenuController::class)->except(['show']);
// -------------------------------------------------------------

Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/checkout/success', function () { return view('customer.maminko.success'); })->name('checkout.success');

// Rute untuk profil user biasa
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
