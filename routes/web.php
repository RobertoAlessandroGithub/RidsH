<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AdminMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| provides the "web" middleware group. Now create something great!
|
*/

// ======================================================================
// RUTE PUBLIK & AUTENTIKASI (Bisa diakses semua orang)
// ======================================================================

// Halaman Utama
Route::get('/', function () {
    return view('layouts.main');
})->name('home');

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

// Rooms
Route::get('/rooms', function () {
    return view('customer.rooms.kamar');
})->name('rooms.index');

Route::get('/kamar-deluxe', function () {
    return view('customer.rooms.kamar-deluxe');
})->name('rooms.deluxe');

// ======================================================================
// RUTE UNTUK CUSTOMER (Tampilan Menu, Keranjang, Checkout)
// ======================================================================

// Halaman utama menu untuk customer
Route::get('/maminko', [MenuController::class, 'maminkoIndex'])->name('maminko.index');

// Halaman detail menu untuk customer
Route::get('/menu/{slug}', [MenuController::class, 'showDetail'])->name('menu.detail');

// Keranjang Belanja (Cart)
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/checkout/success', function () {
    return view('customer.maminko.success');
})->name('checkout.success');


// ======================================================================
// RUTE UNTUK USER YANG SUDAH LOGIN (NON-ADMIN)
// ======================================================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Profil User Biasa
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ======================================================================
// GRUP ROUTE KHUSUS ADMIN (DIJAGA OLEH MIDDLEWARE)
// ======================================================================
Route::middleware(['auth', AdminMiddleware::class]) // Menggunakan 'auth' dan middleware admin Anda
    ->prefix('admin') // Semua URL diawali dengan /admin
    ->name('admin.') // Semua nama rute diawali dengan admin.
    ->group(function () {

    // Dashboard: /admin/dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Menu: /admin/menu, /admin/menu/create, dll.
    Route::resource('menu', MenuController::class);
    Route::post('menu/{menu}/toggle-status', [MenuController::class, 'toggleActiveStatus'])->name('menu.toggle-status');

    // Categories: /admin/categories, /admin/categories/create, dll.
    Route::resource('categories', CategoryController::class);

    // Orders: /admin/orders, /admin/orders/{id}, dll.
    Route::resource('orders', OrderController::class);

    // Users (jika ada manajemen user oleh admin)
    // Route::resource('users', UserController::class);

    // Reports: /admin/reports, /admin/reports/daily-sales
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('daily-sales', [ReportController::class, 'dailySales'])->name('daily-sales');
    });

    // Settings: /admin/settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });
});
