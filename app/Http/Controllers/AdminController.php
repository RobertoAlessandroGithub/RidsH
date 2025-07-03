<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Menghitung ringkasan data untuk kartu statistik
        $summary = [
            'new_orders' => Order::where('status', 'pending')->count(),
            'preparing_orders' => Order::where('status', 'preparing')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'completed_orders_today' => Order::where('status', 'completed')
                                             ->whereDate('updated_at', Carbon::today())
                                             ->count(),
        ];

        // ===================================================
        // PERBAIKAN DI SINI
        // ===================================================
        // Query ini sekarang hanya mengambil pesanan yang relevan untuk dashboard:
        // 1. Dibuat HARI INI.
        // 2. Statusnya BUKAN 'completed' atau 'cancelled'.
        $activeOrders = Order::whereDate('created_at', Carbon::today())
                             ->whereIn('status', ['pending', 'preparing', 'ready'])
                             ->latest() // Mengurutkan dari yang terbaru
                             ->get();

        // Mengirim data yang sudah difilter ke view
        return view('admin.dashboard', compact('summary', 'activeOrders'));
    }
}
