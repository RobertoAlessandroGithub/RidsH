{{-- Asumsi Anda memiliki layout parent seperti 'layouts.admin' --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin Restoran')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard Restoran</h1>

    {{-- Bagian Kartu Ringkasan --}}
    <div class="row">
        {{-- Kartu Pesanan Baru --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pesanan Baru Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingOrdersToday ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="stretched-link text-muted text-decoration-none small mt-2 d-block">Lihat Semua Pesanan Baru</a>
                </div>
            </div>
        </div>

        {{-- Kartu Item Menu Aktif --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Item Menu Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeMenuItems ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="{{ route('menu.index') }}" class="stretched-link text-muted text-decoration-none small mt-2 d-block">Kelola Menu</a>
                </div>
            </div>
        </div>

        {{-- Kartu Total Pendapatan Hari Ini --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Penjualan Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($dailyRevenue ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="#" class="stretched-link text-muted text-decoration-none small mt-2 d-block">Lihat Laporan Penjualan</a>
                </div>
            </div>
        </div>

        {{-- Kartu Menu Terlaris --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menu Terpopuler</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $topSellingDishName ?? 'N/A' }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <a href="#" class="stretched-link text-muted text-decoration-none small mt-2 d-block">Laporan Menu</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Aktivitas Terkini / Pesanan Terbaru --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                </div>
                <div class="card-body">
                    @if(count($recentOrders ?? []) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Pelanggan/Meja</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name ?? ($order->table_number ? 'Meja ' . $order->table_number : 'Tamu') }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = '';
                                            switch($order->status) {
                                                case 'pending': $badgeClass = 'bg-warning text-dark'; break;
                                                case 'preparing': $badgeClass = 'bg-info'; break;
                                                case 'ready': $badgeClass = 'bg-primary'; break;
                                                case 'completed': $badgeClass = 'bg-success'; break;
                                                case 'cancelled': $badgeClass = 'bg-danger'; break;
                                                default: $badgeClass = 'bg-secondary'; break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">Lihat Semua Pesanan &rarr;</a>
                    </div>
                    @else
                        <p class="text-center">Tidak ada pesanan terbaru.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            {{-- Bagian Link Cepat --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tindakan Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('menu.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>Tambah Menu Baru
                        </a>
                        <a href="{{ route('menu.index') }}" class="btn btn-info btn-lg">
                            <i class="fas fa-utensils me-2"></i>Kelola Semua Menu
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-clipboard-list me-2"></i>Perbarui Status Pesanan
                        </a>
                        {{-- Anda perlu membuat route untuk kategori --}}
                        <a href="#" class="btn btn-secondary btn-lg">
                            <i class="fas fa-tags me-2"></i>Kelola Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
