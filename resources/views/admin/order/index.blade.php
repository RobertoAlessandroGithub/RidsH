@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pesanan</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4 rounded-xl">
        {{-- =================================================== --}}
        {{-- PERBAIKAN DI SINI: Menambahkan Form Filter & Search --}}
        {{-- =================================================== --}}
        <div class="card-header py-3">
            <form action="{{route('admin.orders.index')}}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari Nama atau Kode Pesanan..." value="{{ request('search') }}">
                </div>

                <div class="col-lg-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Dari</span>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="Dari Tanggal">
                        <span class="input-group-text">Sampai</span>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="Sampai Tanggal">
                    </div>
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i> Filter</button>
                </div>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>No. Meja</th>
                            <th>Nomor HP</th>
                            <th>Menu</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-bold align-middle">{{"MMK-".$order->id}}</td>
                                <td class="align-middle">{{ $order->customer_name ?? 'N/A' }}</td>
                                <td class="align-middle">{{ $order->table_number ?? 'N/A' }}</td>
                                 <td class="align-middle">{{ $order->customer_phone ?? 'N/A' }}</td>
                                <td class="align-middle">
                                    @if($order->items->count())
                                        <ul class="mb-0 ps-3">
                                            @foreach ($order->items->take(2) as $item)
                                                <li>{{ $item->quantity }}x {{ $item->menu->name ?? '-' }}</li>
                                            @endforeach
                                            @if($order->items->count() > 2)
                                                <li class="text-muted"><em>+ {{ $order->items->count() - 2 }} item lainnya...</em></li>
                                            @endif
                                        </ul>
                                    @else
                                        <em>-</em>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <span class="badge rounded-pill @if($order->status == 'pending') bg-warning text-dark @elseif($order->status == 'preparing') bg-info @elseif($order->status == 'ready') bg-primary @elseif($order->status == 'completed') bg-success @else bg-danger @endif text-white px-2 py-1">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="align-middle">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-secondary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        {{-- Sebaiknya gunakan route terpisah untuk update status jika ada, misal 'orders.updateStatus' --}}
                                        {{-- Untuk sementara, ini akan mengarah ke route 'update' standar --}}
                                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-inline ms-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="if(confirm('Ubah status?')) { this.form.submit(); }">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">Tidak ada pesanan yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Menambahkan link paginasi --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection
