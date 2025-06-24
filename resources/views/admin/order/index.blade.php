    {{-- resources/views/admin/order/index.blade.php --}}

    @extends('layouts.admin')

    @section('title', 'Manajemen Pesanan')

    @section('content')
        <h1 class="h3 mb-4 text-gray-800">Manajemen Pesanan</h1>

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
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor Meja</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                                <th>Tanggal Pesan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $order->table_number ?? 'N/A' }}</td>
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
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary btn-sm rounded-full me-2">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        {{-- Form untuk update status --}}
                                        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada pesanan yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection
