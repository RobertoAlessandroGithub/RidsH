<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Asumsi Anda memiliki model Order
use App\Models\Menu;  // Asumsi Anda memiliki model Menu
use Carbon\Carbon; // Untuk bekerja dengan tanggal

class AdminController extends Controller
{
    public function dashboard()
    {
        // Ambil ringkasan data untuk dashboard
        $newOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $readyOrders = Order::where('status', 'ready')->count();
        $completedOrdersToday = Order::where('status', 'completed')
                                    ->whereDate('updated_at', Carbon::today())
                                    ->count();

        // Ambil beberapa pesanan terbaru
        $recentOrders = Order::orderBy('created_at', 'desc')->take(10)->get();

        $summary = [
            'new_orders' => $newOrders,
            'preparing_orders' => $preparingOrders,
            'ready_orders' => $readyOrders,
            'completed_orders_today' => $completedOrdersToday,
        ];

        return view('admin.dashboard', compact('summary', 'recentOrders'));
    }
}
