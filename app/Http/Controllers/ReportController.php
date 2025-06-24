<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports (index page for reports).
     */
    public function index()
    {
        // Ini adalah halaman utama laporan, bisa menampilkan ringkasan atau daftar laporan yang tersedia.
        // Untuk contoh ini, kita akan menampilkan total penjualan sederhana.

        // Menghitung total penjualan dari pesanan yang completed
        $totalSales = Order::where('status', 'completed')->sum('total_amount');

        // Menghitung jumlah pesanan yang completed
        $completedOrdersCount = Order::where('status', 'completed')->count();

        // Anda bisa menambahkan data ringkasan lain di sini jika diperlukan
        $data = [
            'totalSales' => $totalSales,
            'completedOrdersCount' => $completedOrdersCount,
            // ... data laporan lainnya
        ];

        return view('admin.reports.index', compact('data'));
    }

    /**
     * Example of a more specific report (e.g., Daily Sales).
     */
    public function dailySales(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $dailyOrders = Order::where('status', 'completed')
                            ->whereDate('created_at', $date)
                            ->get();

        $dailyTotalSales = $dailyOrders->sum('total_amount');

        return view('admin.reports.daily-sales', compact('dailyOrders', 'dailyTotalSales', 'date'));
    }

    // Anda bisa menambahkan method lain untuk laporan yang berbeda, misalnya:
    // public function popularMenus() { ... }
    // public function monthlySales() { ... }
}
