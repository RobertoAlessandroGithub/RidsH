@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h2 fw-bold text-gray-800">Manajemen Pesanan</h1>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Cari Nama atau Kode Pesanan..." value="{{ request('search') }}">
            </div>
            <div class="col-lg-4">
                <div class="input-group input-group-lg">
                    <span class="input-group-text">Dari</span>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    <span class="input-group-text">Sampai</span>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-lg">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <div class="row">
            @forelse ($orders as $order)
                @php
                    $statusColor = match($order->status) {
                        'pending' => 'warning',
                        'preparing' => 'info',
                        'ready' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    };
                @endphp

                <div class="col-md-6 mb-4">
                    <div class="card border-start border-5 border-{{ $statusColor }} shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="mb-3">
                                    <h4 class="fw-bold mb-1">#{{ $order->id }} - {{ $order->customer_name ?? 'N/A' }}</h4>
                                    <p class="fs-5 mb-1">Meja: <strong>{{ $order->table_number ?? '-' }}</strong></p>
                                    <p class="fs-6">HP: {{ $order->customer_phone ?? '-' }}</p>
                                </div>

                                <div class="mb-3">
                                    <h5 class="fw-semibold">Menu:</h5>
                                    <ul class="ps-3 fs-2">
                                        @foreach ($order->items->take(3) as $item)
                                            <li>{{ $item->quantity }}x {{ $item->menu->name ?? '-' }}</li>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                            <li class="text-muted"><em>+ {{ $order->items->count() - 3 }} item lainnya...</em></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-{{ $statusColor }} fs-6 px-3 py-2 text-uppercase">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div class="mt-3 d-flex gap-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        {{-- The form for status update --}}
                                        <form id="statusUpdateForm-{{ $order->id }}" action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-select form-select-sm order-status-select" data-order-id="{{ $order->id }}">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <h4>Tidak ada pesanan ditemukan.</h4>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelects = document.querySelectorAll('.order-status-select');

        statusSelects.forEach(select => {
            // Store the initial status to revert if the user cancels the confirmation
            select.setAttribute('data-original-status', select.value);

            select.addEventListener('change', function() {
                const selectedStatus = this.value;
                const orderId = this.dataset.orderId;
                let confirmationMessage = '';

                if (selectedStatus === 'cancelled') {
                    confirmationMessage = 'Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.';
                } else {
                    confirmationMessage = `Apakah Anda yakin ingin mengubah status pesanan menjadi '${selectedStatus}'?`;
                }

                if (confirm(confirmationMessage)) {
                    // Submit the form associated with this select
                    document.getElementById(`statusUpdateForm-${orderId}`).submit();
                } else {
                    // Revert to the original selected value if canceled
                    const originalStatus = this.getAttribute('data-original-status');
                    this.value = originalStatus;
                }
            });
        });
    });
</script>
@endpush