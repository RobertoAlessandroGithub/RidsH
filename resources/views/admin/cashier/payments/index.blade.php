{{-- resources/views/admin/cashier/payments/index.blade.php --}}

@extends('layouts.admin') {{-- Sesuaikan dengan layout admin Anda --}}

@section('title', 'Pengecekan Pembayaran Kasir')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Pengecekan Pembayaran Pesanan</h1>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-lg" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4 rounded-xl">
        <div class="card-header py-3 bg-info text-white rounded-t-xl">
            <h6 class="m-0 font-weight-bold">Daftar Pesanan</h6>
        </div>
        <div class="card-body">
            {{-- Filter dan Pencarian --}}
            <form action="{{ route('admin.cashier.payments') }}" method="GET" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Pesanan:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ ($currentFilters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="preparing" {{ ($currentFilters['status'] ?? '') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ ($currentFilters['status'] ?? '') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ ($currentFilters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ ($currentFilters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="is_paid" class="form-label">Status Pembayaran:</label>
                        <select class="form-select" id="is_paid" name="is_paid">
                            <option value="">Semua</option>
                            <option value="0" {{ (isset($currentFilters['is_paid']) && $currentFilters['is_paid'] === '0') ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="1" {{ (isset($currentFilters['is_paid']) && $currentFilters['is_paid'] === '1') ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari (Nama/ID/Kode):</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ $currentFilters['search'] ?? '' }}" placeholder="Cari pesanan...">
                    </div>
                     <div class="col-md-3">
                        <label for="date_from" class="form-label">Dari Tanggal:</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ $currentFilters['date_from'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Sampai Tanggal:</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ $currentFilters['date_to'] ?? '' }}">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary rounded-lg">Filter</button>
                        <a href="{{ route('admin.cashier.payments') }}" class="btn btn-secondary rounded-lg">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Nomor HP</th> {{-- KOLOM BARU DITAMBAHKAN --}}
                            <th>No. Meja</th>
                            <th>Jumlah Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Tanggal Pesan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->order_code ?? 'N/A' }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_phone }}</td> 
                                <td>{{ $order->table_number ?? '-' }}</td>
                                <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    @if ($order->is_paid)
                                        <span class="badge bg-success text-white">Sudah Dibayar</span>
                                    @else
                                        <span class="badge bg-danger text-white">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-white">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info rounded-lg mb-1" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if (!$order->is_paid)
                                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_paid" value="1">
                                            <button type="submit" class="btn btn-sm btn-success rounded-lg mb-1" title="Tandai Sudah Dibayar"
                                                onclick="return confirm('Apakah Anda yakin ingin menandai pesanan ini sudah dibayar?')">
                                                <i class="fas fa-money-bill-wave"></i> Checklist Pembayaran
                                            </button>
                                        </form>
                                    @else
                                        {{-- Jika sudah dibayar, bisa ada opsi untuk membatalkan pembayaran (jika diperlukan) atau tidak ada aksi --}}
                                        {{-- <form action="{{ route('admin.orders.markPaid', $order->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="is_paid" value="0">
                                            <button type="submit" class="btn btn-sm btn-warning rounded-lg" title="Batalkan Pembayaran"
                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan status pembayaran pesanan ini?')">
                                                <i class="fas fa-undo"></i> Batalkan Bayar
                                            </button>
                                        </form> --}}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada pesanan ditemukan.</td> {{-- COLSPAN DISESUAIKAN --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection