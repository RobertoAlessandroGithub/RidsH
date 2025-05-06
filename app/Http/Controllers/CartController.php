<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;

class CartController extends Controller
{
    // Tambah item ke cart
    public function addToCart(Request $request)
    {
        $cart = session('cart', []);

        // Ambil menu dari database
        $menu = Menu::find($request->id);

        if (!$menu) {
            return response()->json(['message' => 'Menu tidak ditemukan.'], 404);
        }

        // Kalau item sudah ada, tinggal update quantity
        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] += $request->quantity;
        } else {
            // Kalau belum ada, tambahkan ke cart
            $cart[$request->id] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $request->quantity,
                'image' => $menu->image
            ];
        }

        // Simpan kembali ke session
        session(['cart' => $cart]);

        return response()->json([
            'cart_count' => count($cart),
            'message' => $menu->name . ' berhasil ditambahkan ke keranjang.'
        ]);
    }

    // Tampilkan halaman cart
    public function showCart()
    {
        $cart = session('cart', []); // Mengambil cart dari session, default ke array kosong
    
        // Pastikan cart adalah array
        if (!is_array($cart)) {
            $cart = [];
        }
    
        $totalPrice = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    
        return view('customer.cart', [
            'cartItems' => $cart,
            'totalPrice' => $totalPrice
        ]);
    }

    // Hapus item dari cart
    public function removeFromCart($id)
    {
        $cart = session('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
        }

        return redirect('/cart')->with('success', 'Item berhasil dihapus dari keranjang.');
    }
    public function checkout()
{
    $cart = session('cart', []); // Mengambil cart dari session, default ke array kosong

    // Pastikan cart adalah array
    if (!is_array($cart)) {
        $cart = [];
    }

    if (empty($cart)) {
        return redirect('/cart')->with('error', 'Keranjang masih kosong!');
    }

    $totalPrice = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    return view('customer.checkout', [
        'cartItems' => $cart, // Mengirimkan cartItems ke view
        'totalPrice' => $totalPrice
    ]);
}

public function processCheckout(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string|max:255',
        'table_number' => 'required|string|max:10'
    ]);

    // Ambil cart dari session
    $cart = session('cart', []);

    // Pastikan cart adalah array dan tidak kosong
    if (!is_array($cart) || empty($cart)) {
        return redirect('/cart')->with('error', 'Keranjang masih kosong!');
    }

    // Hitung total harga
    $totalPrice = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    // Simpan pesanan ke database
    $order = new Order();
    $order->name = $request->name;
    $order->table_number = $request->table_number;
    $order->cart_items = json_encode($cart); // Simpan item keranjang sebagai JSON
    $order->total_price = $totalPrice;
    $order->save();

    // Hapus keranjang setelah checkout
    session()->forget('cart');

    // Redirect ke halaman utama dengan pesan sukses
    return redirect('/')->with('success', 'Pesanan Anda berhasil dikirim! Terima kasih.');
}
}
