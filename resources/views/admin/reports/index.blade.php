{{-- resources/views/admin/reports/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Laporan Restoran')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Laporan & Statistik Restoran</h1>

    <div class="row">
        {{-- Total Penjualan --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Penjualan (Semua Waktu)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($data['totalSales'] ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jumlah Pesanan Selesai --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Jumlah Pesanan Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['completedOrdersCount'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contoh Kartu Laporan Lain (bisa diganti atau dihapus) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Laporan Kustom</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Siap Disesuaikan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 rounded-xl">
                <div class="card-header py-3 bg-gray-50 rounded-t-xl">
                    <h6 class="m-0 font-weight-bold text-primary">Jenis Laporan Tersedia</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.reports.daily-sales') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-lg mb-2">
                            Laporan Penjualan Harian
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-lg mb-2 disabled">
                            Laporan Menu Terlaris (Belum Implemented)
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-lg disabled">
                            Laporan Stok (Belum Implemented)
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
