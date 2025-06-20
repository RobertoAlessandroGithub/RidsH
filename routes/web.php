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
    return view('layouts.main');
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

// Rooms
Route::get('/rooms', function () {
    return view('customer.rooms.kamar');
});

// Rooms-Deluxe
Route::get('/kamar-deluxe', function () {
    return view('customer.rooms.kamar-deluxe');
});
//maminko
Route::get('/maminko', function () {
    return view('customer.maminko.maminko');
});

Route::get('/menu-detail', function () {
    return view('customer.maminko.menu-detail');
});

Route::get('/menu/{name}', function ($name) {
    // nanti kamu bisa query database atau array di sini berdasarkan nama
    $decodedName = urldecode($name);

    // Simulasi pencarian data menu (nantinya bisa dari DB)
    $menus = [
        'Red Dragon' => [
            'name' => 'Red Dragon',
            'desc' => 'Salmon, Philadelphia cheese, cucumber, avocado',
            'price' => 17000,
            'image' => '/images/makanan.jpg',
        ],
        'Japanese Salad With Shrimps' => [
            'name' => 'Japanese Salad With Shrimps',
            'desc' => 'Shrimp, greens, sesame dressing',
            'price' => 20000,
            'image' => '/images/makanan.jpg',
        ],
        'Maki Tuna' => [
            'name' => 'Maki Tuna',
            'desc' => 'Tuna, rice, nori seaweed',
            'price' => 15000,
            'image' => '/images/makanan.jpg',
        ],
        'Salmon Sashimi' => [
            'name' => 'Salmon Sashimi',
            'desc' => 'Fresh salmon slices',
            'price' => 22000,
            'image' => '/images/makanan.jpg',
        ],
    ];

    // Cek apakah menu tersedia
    if (!isset($menus[$decodedName])) {
        abort(404, 'Menu tidak ditemukan.');
    }

    $menu = $menus[$decodedName];

    return view('customer.maminko.menu-detail', compact('menu'));
})->name('menu.detail');

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
