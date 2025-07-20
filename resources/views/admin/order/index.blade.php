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
                                    <p class="fs-3 mb-1">Meja: <strong>{{ $order->table_number ?? '-' }}</strong></p>
                                    <p class="fs-4">HP: {{ $order->customer_phone ?? '-' }}</p>
                                    @if ($order->notes)
                                        <p class="fs-3 mb-1">Catatan: <em>{{ $order->notes }}</em></p>
                                    @endif
                                    @if ($order->status === 'cancelled' && $order->cancel_notes)
                                        <p class="fs-3 mb-1 text-danger">Catatan Pembatalan: <em>{{ $order->cancel_notes }}</em></p>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <h5 class="fw-semibold">Menu:</h5>
                                    <ul class="ps-3 fs-2">
                                        @foreach ($order->items->take(10) as $item)
                                            <li>{{ $item->quantity }}x {{ $item->menu->name ?? '-' }}</li>
                                        @endforeach
                                        @if ($order->items->count() > 10)
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
                                        {{-- The select element will trigger the modal for 'cancelled' status --}}
                                        <select name="status" class="form-select form-select-sm order-status-select" data-order-id="{{ $order->id }}">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
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

<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Batalkan Pesanan <span id="modalOrderId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelOrderForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.</p>
                    <div class="mb-3">
                        <label for="cancel_notes" class="form-label">Catatan Pembatalan (opsional):</label>
                        <textarea class="form-control" id="cancel_notes" name="cancel_notes" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="status" value="cancelled">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Konfirmasi Pembatalan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelects = document.querySelectorAll('.order-status-select');
        const cancelOrderModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        const modalOrderIdSpan = document.getElementById('modalOrderId');
        const cancelOrderForm = document.getElementById('cancelOrderForm');
        const cancelNotesTextarea = document.getElementById('cancel_notes');

        statusSelects.forEach(select => {
            select.setAttribute('data-original-status', select.value);

            select.addEventListener('change', function() {
                const selectedStatus = this.value;
                const orderId = this.dataset.orderId;
                const originalStatus = this.getAttribute('data-original-status');

                if (selectedStatus === 'cancelled') {
                    // Set the order ID in the modal title
                    modalOrderIdSpan.textContent = `#${orderId}`;
                    // Set the form action for the modal
                    cancelOrderForm.action = `/admin/orders/${orderId}`; // Adjust this route if needed
                    // Clear previous notes
                    cancelNotesTextarea.value = '';
                    // Show the modal
                    cancelOrderModal.show();
                    // Revert the select value temporarily to the original until modal is confirmed
                    this.value = originalStatus;
                } else {
                    // For other status changes, use a direct confirmation and submission
                    let confirmationMessage = `Apakah Anda yakin ingin mengubah status pesanan menjadi '${selectedStatus}'?`;
                    if (confirm(confirmationMessage)) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/orders/${orderId}`; // Adjust this route if needed
                        form.innerHTML = `
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="${selectedStatus}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        // Revert to the original selected value if canceled
                        this.value = originalStatus;
                    }
                }
            });
        });

        // Event listener for when the modal is hidden
        cancelOrderModal._element.addEventListener('hidden.bs.modal', function () {
            // No need to revert select value here as it's already reverted
            // The form will only be submitted if the user confirms inside the modal
        });
    });
</script>
@endpush