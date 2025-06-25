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
use App\Http\Controllers\AdminController; // Pastikan ini diimpor jika Anda menggunakan AdminController@dashboard

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

// ==========================
// Halaman Utama
// ==========================
Route::get('/', function () {
    return view('layouts.main');
})->name('home');

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

// ======================================================================
// ROUTE ADMIN (SEMENTARA PUBLIK UNTUK DEBUGGING - HARAP KEMBALIKAN KE MIDDLEWARE 'admin' NANTI)
// ======================================================================

// Admin Dashboard
// Akses URL: http://localhost:8000/admin/dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Menu CRUD (Resourceful routes untuk Admin, sekarang publik sementara)
// Ini akan menangani semua operasi CRUD menu
// Contoh URL:
// - Daftar Menu: http://localhost:8000/menu
// - Form Tambah: http://localhost:8000/menu/create
// - Submit Tambah: POST ke http://localhost:8000/menu
Route::resource('menu', MenuController::class);
// Route kustom untuk mengubah status aktif/nonaktif menu
// URL: POST /menu/{menu}/toggle-status
Route::post('/menu/{menu}/toggle-status', [App\Http\Controllers\MenuController::class, 'toggleActiveStatus'])->name('menu.toggle-status');

// Laporan Admin
Route::prefix('admin/reports')->name('admin.reports.')->group(function () {
    Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
    Route::get('/daily-sales', [App\Http\Controllers\ReportController::class, 'dailySales'])->name('daily-sales');
});

// Pengaturan Admin
Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
    Route::put('/', [App\Http\Controllers\SettingController::class, 'update'])->name('update');
});

// Route khusus untuk tampilan delete menu (jika ada tampilan terpisah)
// Akses URL: http://localhost:8000/admin/menu/delete-view
Route::get('/admin/menu/delete-view', [MenuController::class, 'deleteView'])->name('admin.menu.delete-view');

// Manajemen Pesanan (Resourceful routes untuk Admin, sekarang publik sementara)
// Contoh URL:
// - Daftar Pesanan: http://localhost:8000/orders
Route::resource('orders', OrderController::class);


// ======================================================================
// AKHIR DARI ROUTE ADMIN SEMENTARA PUBLIK
// ======================================================================
Route::resource('categories', CategoryController::class);

// ==========================
// Dashboard untuk user login biasa (dilindungi middleware 'auth' dan 'verified')
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard standar untuk user biasa (jika ada)
    // Akses URL: http://localhost:8000/dashboard (setelah login sebagai user biasa)


    // Profil User Biasa
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // Ini mengarah ke resources/views/dashboard.blade.php
    })->name('dashboard');

    Route::get('/create', function () {
        return view('admin.menu.create'); // Ini mengarah ke resources/views/dashboard.blade.php
    })->name('create');


// ==========================
// General Public Routes (bisa diakses tanpa login)
// ==========================

// Rooms
Route::get('/rooms', function () {
    return view('customer.rooms.kamar');
})->name('rooms.index');

// Rooms-Deluxe
Route::get('/kamar-deluxe', function () {
    return view('customer.rooms.kamar-deluxe');
})->name('rooms.deluxe');


// ==========================
// Maminko Menu & Checkout (Customer-facing)
// ==========================

// Route utama untuk halaman Maminko (menu customer)s
// Akses URL: http://localhost:8000/maminko

Route::get('/maminko', [MenuController::class, 'maminkoIndex'])->name('maminko.index');
// Custom detail route pakai slug
Route::get('/menu/{slug}', [MenuController::class, 'showDetail'])->name('menu.detail');

// Baru resource route tanpa show
Route::resource('menu', MenuController::class)->except(['show']);
// Route untuk menampilkan detail satu menu (customer)

// Route untuk menampilkan halaman checkout (GET)
// Akses URL: http://localhost:8000/checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Route untuk menambahkan item ke keranjang
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
// Route untuk menampilkan halaman keranjang
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
// Route untuk menghapus item dari keranjang
Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/checkout', function () {
    return view('customer.maminko.checkout');
})->name('checkout.index');

Route::get('/order/success', function () {
    return view('customer.maminko.success');
})->name('order.success');

// Route untuk memproses pesanan dari halaman checkout (POST)
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');



// ==========================
// Route Otomatis dari Laravel Breeze / Jetstream (jika digunakan)
// ==========================
// require __DIR__.'/auth.php';
