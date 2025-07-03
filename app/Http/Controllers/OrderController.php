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
    public function index(Request $request)
    {
        // ===================================================
        // PERBAIKAN DI SINI: Menambahkan logic filter & search
        // ===================================================
        $query = Order::with('items.menu')->latest();

        // Filter berdasarkan Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian berdasarkan Nama, ID, atau Kode Pesanan
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('order_code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', $searchTerm);
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }


        // Terapkan Pagination
        $orders = $query->paginate(15)->withQueryString(); // withQueryString() agar filter tetap ada saat pindah halaman

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
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
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'table_number' => $request->table_number,
                'notes' => $request->order_notes,
                'payment_method' => $request->payment_method,
                'total_amount' => $request->final_total,
                'status' => 'pending',
            ]);

            $cart = json_decode($request->cart_data, true);
            foreach ($cart as $name => $item) {
                $menu = Menu::where('name', $name)->firstOrFail();
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }
            DB::commit();
            return redirect()->route('checkout.success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Pesanan gagal: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load('items.menu');
        return view('admin.order.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // Sebaiknya gunakan fungsi terpisah untuk update status
        // agar tidak bentrok dengan route 'update' standar dari resource
        $request->validate(['status' => 'required|string|in:pending,preparing,ready,completed,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
