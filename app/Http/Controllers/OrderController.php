<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->get();
        return view('admin.order.index', compact('orders'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Validasi data dari form submit
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'table_number' => 'required|string|max:255',
            'order_notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:cash,qr',
            'cart_data' => 'required|json',
            'final_total' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();

        try {
            // Simpan order utama
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'table_number' => $request->table_number,
                'notes' => $request->order_notes,
                'payment_method' => $request->payment_method,
                'total_amount' => $request->final_total,
                'status' => 'pending',
            ]);

            // Decode cart data
            $cart = json_decode($request->cart_data, true);

            // Simpan item-item pesanan
            foreach ($cart as $name => $item) {
                $menu = Menu::where('name', $name)->first();

                if (!$menu) {
                    DB::rollBack();
                    return back()->with('error', "Menu '$name' tidak ditemukan.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            return redirect()->route('checkout.success');
             return redirect()->route('order.show', ['order' => $order->id]);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Pesanan gagal: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Pesanan gagal: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load('items.menu');
        return view('admin.order.show', compact('order'));

     $order->load('items.menu'); // Memuat item untuk pesanan tunggal ini
    return view('receipt', compact('order')); // Lalu, melewatkannya sebagai $order
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:pending,preparing,ready,completed,cancelled',
        ]);

        $order->update($validatedData);

        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
