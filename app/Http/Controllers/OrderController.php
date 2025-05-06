<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table; // Ditambahkan
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{

    public function index()
    {
        // Ambil semua pesanan dari database
        $orders = Order::all();

        // Kembalikan view dengan data pesanan
        return view('orders.index', compact('orders'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric|min:1'
        ]);

        $menuId = $request->id;
        $menuName = $request->name;
        $price = $request->price;
        $quantity = $request->quantity;
    
        $cart = session()->get('cart', []);
    
        // Kalau barang sudah ada di cart, tambahkan qty-nya
        if (isset($cart[$menuId])) {
            $cart[$menuId]['quantity'] += $quantity;
        } else {
            $cart[$menuId] = [
                'name' => $menuName,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }
    
        session()->put('cart', $cart);
    
        return response()->json([
            'message' => 'Menu ditambahkan ke keranjang',
            'cart_count' => count($cart) // Untuk menampilkan jumlah item di cart
        ]);
    }
    
    public function cartView()
    {
        $cart = session()->get('cart', []);
        $tables = Table::all();
    
        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    
        return view('customer.checkout', compact('cart', 'tables', 'total'));
    }
    
    public function clearCart()
    {
        session()->forget('cart');
        return redirect('/menu')->with('success', 'Keranjang dikosongkan.');
    }

    public function checkoutView()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/menu')->with('error', 'Keranjang belanja kosong.');
        }
        
        $tables = Table::all();
        return view('customer.checkout', compact('cart', 'tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'notes' => 'nullable|string'
        ]);
        
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }
        
        // Hitung total
        $total = 0;
        $items = [];
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
            $items[] = [
                'id' => $id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ];
        }
        
        // Simpan order ke database
        $order = Order::create([
            'user_id' => auth()->id() ?? null,
            'table_id' => $request->table_id,
            'total' => $total,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);
        
        // Simpan item order
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }
        
        // Generate payment URL (Midtrans)
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        
        $params = [
            'transaction_details' => [
                'order_id' => $order->id.'-'.time(),
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Guest',
                'email' => auth()->user()->email ?? 'guest@example.com',
            ]
        ];
        
        try {
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            
            // Kosongkan keranjang setelah checkout
            session()->forget('cart');
            
            return response()->json([
                'payment_url' => $paymentUrl,
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Pembayaran gagal: '.$e->getMessage()
            ], 500);
        }
    }
}