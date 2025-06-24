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
            // Validasi data yang masuk
            $validatedData = $request->validate([
                'customer_name' => 'required|string|max:255', // Nama sekarang required
                'customer_phone' => 'nullable|string|max:20', // Tambahkan validasi untuk nomor telepon
                'table_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                'order_items' => 'required|array',
                'order_items.*.menu_name' => 'required|string|max:255',
                'order_items.*.quantity' => 'required|integer|min:1',
                'order_items.*.price_at_order' => 'required|numeric|min:0',
                'payment_method' => 'required|string|in:cash,qr', // Tambahkan validasi metode pembayaran
            ]);

            DB::beginTransaction();

            try {
                // Buat pesanan utama
                $order = Order::create([
                    'customer_name' => $validatedData['customer_name'],
                    'customer_phone' => $validatedData['customer_phone'], // Simpan nomor telepon
                    'table_number' => $validatedData['table_number'],
                    'total_amount' => $validatedData['total_amount'],
                    'notes' => $validatedData['notes'],
                    'status' => 'pending',
                    'payment_method' => $validatedData['payment_method'], // Simpan metode pembayaran
                ]);

                // Simpan setiap item pesanan
                foreach ($validatedData['order_items'] as $itemData) {
                    $menu = Menu::where('name', $itemData['menu_name'])->first();

                    if (!$menu) {
                        DB::rollBack();
                        return response()->json(['message' => 'Menu "' . $itemData['menu_name'] . '" tidak ditemukan.'], 404);
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $itemData['quantity'],
                        'price' => $itemData['price_at_order'],
                    ]);
                }

                DB::commit();

                return response()->json(['message' => 'Pesanan berhasil dibuat dan disimpan!'], 201);

            } catch (\Illuminate\Validation\ValidationException $e) {
                DB::rollBack();
                return response()->json(['errors' => $e->errors()], 422);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Gagal membuat pesanan: ' . $e->getMessage()], 500);
            }
        }

        /**
         * Display the specified order.
         */
        public function show(Order $order)
        {
            $order->load('items.menu');

            return view('admin.order.show', compact('order'));
        }

        /**
         * Update the specified order in storage. (Untuk mengubah status pesanan)
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
