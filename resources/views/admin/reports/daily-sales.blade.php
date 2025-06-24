{{-- resources/views/admin/reports/daily-sales.blade.php --}}

@extends('layouts.admin')

@section('title', 'Laporan Penjualan Harian')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan Harian</h1>

    <div class="card shadow mb-4 rounded-xl">
        <div class="card-header py-3 bg-gray-50 rounded-t-xl">
            <h6 class="m-0 font-weight-bold text-primary">Filter Tanggal</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.daily-sales') }}" method="GET" class="d-flex align-items-center">
                <label for="date" class="form-label me-2 mb-0">Pilih Tanggal:</label>
                <input type="date" class="form-control w-auto me-2 rounded-lg" id="date" name="date" value="{{ $date }}">
                <button type="submit" class="btn btn-primary rounded-lg">Tampilkan Laporan</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4 rounded-xl">
        <div class="card-header py-3 bg-info text-white rounded-t-xl">
            <h6 class="m-0 font-weight-bold">Ringkasan Penjualan Tanggal {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h6>
        </div>
        <div class="card-body">
            <h4 class="text-center text-primary mb-4">Total Penjualan: Rp {{ number_format($dailyTotalSales, 0, ',', '.') }}</h4>

            @if($dailyOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan/Meja</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Waktu Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name ?? ($order->table_number ? 'Meja ' . $order->table_number : 'Tamu') }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge rounded-pill
                                            @if($order->status == 'pending') bg-warning
                                            @elseif($order->status == 'preparing') bg-info
                                            @elseif($order->status == 'ready') bg-primary
                                            @elseif($order->status == 'completed') bg-success
                                            @elseif($order->status == 'cancelled') bg-danger
                                            @endif
                                            text-white px-2 py-1">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-600">Tidak ada pesanan selesai untuk tanggal ini.</p>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-start mt-4">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary rounded-lg">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Laporan Utama
        </a>
    </div>
@endsection
