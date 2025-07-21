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
        $query = Order::with('items.menu')->latest();

        // Default: Hanya tampilkan pesanan yang sudah dibayar di Manajemen Pesanan
        // Kecuali jika filter is_paid secara eksplisit diatur
        if (!$request->filled('is_paid')) {
            $query->where('is_paid', true);
        }

        // Filter berdasarkan Status Pesanan (jika ada)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Status Pembayaran (jika ada dari form)
        if ($request->filled('is_paid') && in_array($request->is_paid, ['0', '1'])) {
            $query->where('is_paid', (bool) $request->is_paid);
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

        // Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Terapkan Pagination
        $orders = $query->paginate(15)->withQueryString();

        // Siapkan filter saat ini untuk menjaga nilai di form filter
        $currentFilters = $request->only(['status', 'search', 'date_from', 'date_to', 'is_paid']);

        return view('admin.order.index', compact('orders', 'currentFilters'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20', // Tambahkan validasi untuk nomor HP
            'table_number' => 'nullable|string|max:255',
            'order_notes' => 'nullable|string',
            'payment_method' => 'required|string|in:cash,transfer',
            'final_total' => 'required|numeric|min:0',
            'cart_data' => 'required|json',
        ]);

        DB::beginTransaction();
        try {
            // Generate order code
            $today = now()->format('Ymd');
            $latestOrder = Order::whereDate('created_at', today())->latest()->first();
            $nextOrderNum = $latestOrder ? (int)substr($latestOrder->order_code, -4) + 1 : 1;
            $orderCode = 'ORD-' . $today . '-' . str_pad($nextOrderNum, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_code' => $orderCode,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone, // Tambahkan ini
                'table_number' => $request->table_number,
                'notes' => $request->order_notes,
                'payment_method' => $request->payment_method,
                'total_amount' => $request->final_total,
                'status' => 'pending', // Status awal
                'is_paid' => false, // Default: belum dibayar saat dibuat
            ]);

            $cart = json_decode($request->cart_data, true);
            foreach ($cart as $name => $item) {
                $menu = Menu::where('name', $name)->firstOrFail();
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['qty'],
                    'price' => $menu->price, // Pastikan harga diambil dari menu aslinya
                ]);
            }
            DB::commit();
            return redirect()->route('checkout.success')->with('order_code', $orderCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Pesanan gagal: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load('items.menu');

        $allMenus = Menu::orderBy('name')->get();

        return view('admin.order.show', compact('order', 'allMenus'));
    }

    public function update(Request $request, Order $order)
    {
        $rules = [];
        if ($request->has('status')) {
            $rules['status'] = 'required|string|in:pending,preparing,ready,completed,cancelled';
        }
        if ($request->has('is_paid')) {
            $rules['is_paid'] = 'required|boolean';
        }

        $validatedData = $request->validate($rules);

        if ($request->has('is_paid')) {
            $order->is_paid = (bool) $request->is_paid;
        }
        if ($request->has('status')) {
            $order->status = $request->status;
        }
        $order->save();

        return back()->with('success', 'Pesanan berhasil diperbarui!');
    }

    public function cashierPayments(Request $request)
    {
        $query = Order::with('items.menu')->latest();

        if (!$request->filled('status') && !$request->filled('is_paid')) {
            $query->where('is_paid', false);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_paid')) {
            $query->where('is_paid', (bool)$request->is_paid);
        }

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

        $orders = $query->paginate(15)->withQueryString();

        $currentFilters = $request->only(['status', 'is_paid', 'search', 'date_from', 'date_to']);

        return view('admin.cashier.payments.index', compact('orders', 'currentFilters'));
    }

    // ======================================================================
    // PERUBAHAN DI SINI
    // ======================================================================
    public function updateItems(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|json',
        ]);

        DB::beginTransaction();
        try {
            // Hapus semua item lama dari pesanan ini
            $order->items()->delete();

            $newItems = json_decode($request->items, true);
            $newTotalAmount = 0;

            if (is_array($newItems)) {
                foreach ($newItems as $itemData) {
                    // Buat kembali item pesanan dengan data yang baru
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $itemData['menu_id'],
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price'],
                    ]);
                    // Hitung ulang total
                    $newTotalAmount += $itemData['quantity'] * $itemData['price'];
                }
            }

            // Update total harga di pesanan utama
            $order->total_amount = $newTotalAmount;
            $order->save();

            DB::commit();
            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pesanan: ' . $e->getMessage());
        }
    }
    // ======================================================================
    // AKHIR DARI PERUBAHAN
    // ======================================================================
}
