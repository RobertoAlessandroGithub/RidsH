{{-- resources/views/admin/order/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Detail Pesanan - ' . $order->id)

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>

    {{-- Form untuk mengupdate pesanan --}}
    <form action="{{ route('admin.orders.updateItems', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Kolom Kiri: Informasi & Item Pesanan --}}
            <div class="col-lg-8">
                <div class="card shadow mb-4 rounded-xl">
                    <div class="card-header py-3 bg-info text-white rounded-t-xl">
                        <h6 class="m-0 font-weight-bold">Informasi Pesanan</h6>
                    </div>
                    <div class="card-body">
                        {{-- Informasi Pelanggan (bisa dibuat jadi input jika ingin diedit juga) --}}
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Nama Pelanggan:</div>
                            <div class="col-md-8">{{ $order->customer_name ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Nomor Meja:</div>
                            <div class="col-md-8">{{ $order->table_number ?? 'N/A' }}</div>
                        </div>
                         <div class="row mb-3">
                            <div class="col-md-4 font-weight-bold">Status:</div>
                            <div class="col-md-8">
                                <span class="badge rounded-pill bg-success text-white px-2 py-1">{{ ucfirst($order->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4 rounded-xl">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Item Pesanan</h6>
                        {{-- Tombol untuk membuka modal tambah item --}}
                        <button type="button" class="btn btn-primary btn-sm rounded-lg" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                            <i class="fas fa-plus me-1"></i> Tambah Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="orderItemsTable">
                                <tbody>
                                    {{-- Item yang sudah ada di-render di sini oleh JavaScript --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan & Tombol Aksi --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4 rounded-xl">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Ringkasan Total</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="font-weight-bold">Total Baru:</h5>
                            <h5 class="font-weight-bold" id="newTotal">Rp 0</h5>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- Hidden input untuk menyimpan data item yang akan dikirim --}}
                        <input type="hidden" name="items" id="itemsInput">
                        <button type="submit" class="btn btn-success w-100 rounded-lg">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100 mt-2 rounded-lg">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal untuk Tambah Item Menu -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuModalLabel">Pilih Menu untuk Ditambahkan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allMenus as $menu)
                                <tr>
                                    <td>{{ $menu->name }}</td>
                                    <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info rounded-lg" onclick="addItem({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }})">
                                            Tambah
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Variabel global untuk menyimpan state item pesanan
    let orderItems = {};

    // Inisialisasi item dari data order yang ada
    @foreach($order->items as $item)
        orderItems['{{ $item->menu->name ?? 'Item Dihapus' }}'] = {
            menu_id: {{ $item->menu_id ?? 'null' }},
            quantity: {{ $item->quantity }},
            price: {{ $item->price }}
        };
    @endforeach

    const tableBody = document.getElementById('orderItemsTable').querySelector('tbody');
    const newTotalEl = document.getElementById('newTotal');
    const itemsInput = document.getElementById('itemsInput');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
    }

    // Fungsi untuk me-render semua item ke dalam tabel
    function renderItems() {
        tableBody.innerHTML = '';
        let total = 0;

        if (Object.keys(orderItems).length === 0) {
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Belum ada item. Silakan tambah item baru.</td></tr>';
        }

        for (const name in orderItems) {
            const item = orderItems[name];
            const itemTotal = item.quantity * item.price;
            total += itemTotal;

            const row = `
                <tr id="item-row-${item.menu_id}">
                    <td class="align-middle">${name}</td>
                    <td class="align-middle" width="150px">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" onclick="updateQuantity('${name}', -1)">-</button>
                            <span class="mx-2 font-weight-bold">${item.quantity}</span>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" onclick="updateQuantity('${name}', 1)">+</button>
                        </div>
                    </td>
                    <td class="align-middle text-end" width="120px">${formatRupiah(itemTotal)}</td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        }

        // Update total dan hidden input
        newTotalEl.textContent = formatRupiah(total);
        itemsInput.value = JSON.stringify(Object.values(orderItems));
    }

    // Fungsi untuk menambah/mengurangi kuantitas
    window.updateQuantity = function(name, change) {
        if (orderItems[name]) {
            orderItems[name].quantity += change;
            if (orderItems[name].quantity <= 0) {
                delete orderItems[name]; // Hapus item jika kuantitas 0 atau kurang
            }
            renderItems();
        }
    }

    // Fungsi untuk menambah item baru dari modal
    window.addItem = function(menuId, name, price) {
        if (orderItems[name]) {
            // Jika item sudah ada, tambah kuantitasnya
            orderItems[name].quantity++;
        } else {
            // Jika item baru, tambahkan ke object
            orderItems[name] = {
                menu_id: menuId,
                quantity: 1,
                price: price
            };
        }
        renderItems();
        // Tutup modal setelah item ditambahkan (opsional)
        var modal = bootstrap.Modal.getInstance(document.getElementById('addMenuModal'));
        modal.hide();
    }

    // Render pertama kali saat halaman dimuat
    renderItems();
});
</script>
@endpush
