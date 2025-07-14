{{-- Menggunakan layout admin yang sudah kita buat sebelumnya --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin Restoran')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard Restoran & Pesanan</h1>

    {{-- Bagian Ringkasan Status Pesanan --}}
    <div class="row">
        {{-- Kartu Pesanan Baru (Pending) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pesanan Baru</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['new_orders'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Pesanan Sedang Dimasak (Preparing) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Sedang Dimasak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['preparing_orders'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Pesanan Siap Diambil/Diantar (Ready) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Siap Diambil/Diantar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['ready_orders'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-concierge-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kartu Pesanan Selesai (Completed Today) --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 rounded-xl">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pesanan Selesai Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['completed_orders_today'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Pesanan Terbaru & Aksi Cepat --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow mb-4 rounded-xl">
                <div class="card-header py-3 flex justify-between items-center bg-gray-50 rounded-t-xl">
                    <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru & Status</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-lg">Lihat Semua Pesanan <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="card-body">
                    @if(count($recentOrders ?? []) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-3 px-4">ID Pesanan</th>
                                    <th class="py-3 px-4">Pelanggan/Meja</th>
                                    <th class="py-3 px-4">Waktu Pesan</th>
                                    <th class="py-3 px-4">Total</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-center">Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop 7-10 pesanan terbaru --}}
                                @foreach($recentOrders as $order)
                                <tr class="border-b last:border-b-0 border-gray-200">
                                    <td class="py-3 px-4">{{ $order->id }}</td>
                                    <td class="py-3 px-4">{{ $order->customer_name ?? ($order->table_number ? 'Meja ' . $order->table_number : 'Tamu') }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('H:i') }} ({{ $order->created_at->diffForHumans() }})</td>
                                    <td class="py-3 px-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @php
                                            $badgeClass = '';
                                            switch($order->status) {
                                                case 'pending': $badgeClass = 'bg-danger'; break; // Merah untuk perlu perhatian
                                                case 'preparing': $badgeClass = 'bg-info'; break; // Biru untuk sedang proses
                                                case 'ready': $badgeClass = 'bg-warning text-dark'; break; // Kuning untuk siap
                                                case 'completed': $badgeClass = 'bg-success'; break; // Hijau untuk selesai
                                                case 'cancelled': $badgeClass = 'bg-secondary'; break; // Abu-abu
                                                default: $badgeClass = 'bg-light text-muted'; break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-full px-3 py-1 text-xs">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-lg" type="button" id="dropdownMenuButton{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Ubah Status
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $order->id }}">
                                                <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'preparing')">Sedang Dimasak</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'ready')">Siap Diantar</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'completed')">Selesai</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">Batalkan</a></li>
                                            </ul>
                                        </div>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info rounded-lg ms-2">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Link Cepat Manajemen Utama (tetap simpel) --}}
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100 py-3 rounded-xl">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-gray-800"><i class="fas fa-utensils me-3 text-primary"></i>Manajemen Menu</h5>
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-primary rounded-full">Kelola Menu <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100 py-3 rounded-xl">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-gray-800"><i class="fas fa-tags me-3 text-success"></i>Manajemen Kategori</h5>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-success rounded-full">Kelola Kategori <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>

    <form id="update-order-status-form" method="POST" style="display: none;">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="order-status-input">
    </form>

    <script>
        // Fungsi untuk mengupdate status pesanan
        function updateOrderStatus(orderId, newStatus) {
            if (confirm(`Apakah Anda yakin ingin mengubah status pesanan ${orderId} menjadi ${newStatus.toUpperCase()}?`)) {
                const form = document.getElementById('update-order-status-form');
                const statusInput = document.getElementById('order-status-input');

                statusInput.value = newStatus;
                form.action = `/orders/${orderId}`; // Sesuaikan dengan route update Anda
                form.submit();
            }
        }
    </script>
@endsection
