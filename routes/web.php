<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
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
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Menu CRUD (Resourceful routes untuk Admin)
// Ini akan menangani:
// GET /menu          -> MenuController@index   (Daftar semua menu)
// GET /menu/create   -> MenuController@create  (Form tambah menu baru)
// POST /menu         -> MenuController@store   (Simpan menu baru)
// GET /menu/{menu}   -> MenuController@show    (Detail menu admin)
// GET /menu/{menu}/edit -> MenuController@edit (Form edit menu)
// PUT/PATCH /menu/{menu} -> MenuController@update (Update menu)
// DELETE /menu/{menu} -> MenuController@destroy (Hapus menu)
Route::resource('menu', MenuController::class);

// Route khusus untuk tampilan delete menu (jika ada tampilan terpisah, biasanya dihandle oleh resource index)
Route::get('/admin/menu/delete-view', [MenuController::class, 'deleteView'])->name('admin.menu.delete-view');

// Manajemen Pesanan (Resourceful routes untuk Admin)
// Ini akan membuat orders.index, orders.show, dll.
Route::resource('orders', OrderController::class);


// ======================================================================
// AKHIR DARI ROUTE ADMIN SEMENTARA PUBLIK
// ======================================================================


// ==========================
// Dashboard untuk user login biasa (dilindungi middleware 'auth' dan 'verified')
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard standar untuk user biasa (jika ada)
    Route::get('/dashboard', function () {
        return view('dashboard'); // View 'dashboard.blade.php' (bukan admin.dashboard)
    })->name('dashboard');

    // Profil User Biasa
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Jika ada route Order History khusus user biasa yang berbeda dengan admin.orders.index
    // Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders.index');
});


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

// Route utama untuk halaman Maminko (menu customer)
// Akan memanggil MenuController@maminkoIndex untuk mendapatkan data menu dari database
Route::get('/maminko', [MenuController::class, 'maminkoIndex'])->name('maminko.index');

// Route untuk menampilkan detail satu menu (customer)
// Akan memanggil MenuController@showDetail untuk mendapatkan detail menu dari database
Route::get('/menu/{name}', [MenuController::class, 'showDetail'])->name('menu.detail');

// Route untuk menampilkan halaman checkout (GET)
// Akan memanggil CheckoutController@index untuk menampilkan form checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Route untuk menambahkan item ke keranjang
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
// Route untuk menampilkan halaman keranjang
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
// Route untuk menghapus item dari keranjang
Route::post('/remove-from-cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');

// Route untuk memproses pesanan dari halaman checkout (POST)
// Akan memanggil OrderController@store untuk menyimpan pesanan ke database
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');


// ==========================
// Route Otomatis dari Laravel Breeze / Jetstream (jika digunakan)
// ==========================
// require __DIR__.'/auth.php';
