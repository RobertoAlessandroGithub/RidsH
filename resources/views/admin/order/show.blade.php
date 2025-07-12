    {{-- resources/views/admin/order/show.blade.php --}}

    @extends('layouts.admin')

    @section('title', 'Detail Pesanan - ' . $order->id)

    @section('content')
        <h1 class="h3 mb-4 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>

        <div class="card shadow mb-4 rounded-xl">
            <div class="card-header py-3 bg-info text-white rounded-t-xl">
                <h6 class="m-0 font-weight-bold">Informasi Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">ID Pesanan:</div>
                    <div class="col-md-8">{{ $order->id }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Nama Pelanggan:</div>
                    <div class="col-md-8">{{ $order->customer_name ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Nomor Meja:</div>
                    <div class="col-md-8">{{ $order->table_number ?? 'N/A' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Total Bayar:</div>
                    <div class="col-md-8">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Status:</div>
                    <div class="col-md-8">
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
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Catatan:</div>
                    <div class="col-md-8">{{ $order->notes ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Tanggal Pesan:</div>
                    <div class="col-md-8">{{ $order->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Terakhir Diperbarui:</div>
                    <div class="col-md-8">{{ $order->updated_at->format('d M Y H:i') }}</div>
                </div>

                {{-- Bagian ini akan menampilkan item-item dalam pesanan --}}
                {{-- Anda perlu memastikan model Order memiliki relasi 'items' ke 'OrderItem' --}}
                {{-- Dan tabel order_items memiliki kolom yang sesuai (order_id, menu_id, quantity, price) --}}
                @if($order->items->count() > 0)
                <h6 class="mt-4 mb-3 font-weight-bold">Item Pesanan:</h6>
                <ul class="list-group">
                    @foreach($order->items as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->menu->name ?? 'Menu Dihapus' }} (x{{ $item->quantity }})
                            <span class="badge bg-info rounded-pill">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                @else
                <p class="text-muted mt-4">Tidak ada item dalam pesanan ini.</p>
                @endif

            </div>
        </div>

        <div class="d-flex justify-content-start mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary rounded-lg">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pesanan
            </a>
            {{-- Tombol untuk mengubah status juga bisa di sini --}}
            {{-- <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-inline ms-2">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="{{ $order->status == 'completed' ? 'pending' : 'completed' }}">
                <button type="submit" class="btn btn-success rounded-lg">
                    <i class="fas fa-check-circle me-1"></i> Tandai Selesai
                </button>
            </form> --}}
        </div>
    @endsection
