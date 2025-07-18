{{-- resources/views/admin/menu/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Kelola Menu Restoran')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Manajemen Menu</h1>

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

    {{-- Bar Pencarian dan Tombol Tambah Menu --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <input type="text" id="searchInput" class="form-control form-control-sm w-25" placeholder="Cari menu...">
        <a href="{{ route('admin.menu.create') }}" class="btn btn-primary rounded-lg shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Menu Baru
        </a>
    </div>

    {{-- Konten Daftar Menu --}}
    <div class="menu-container row" id="menuList">
        @forelse($menus as $menu)
            <div class="col-lg-4 col-md-6 mb-4 menu-card-col" data-menu-id="{{ $menu->id }}">
                <div class="card shadow rounded-xl h-100 d-flex flex-column justify-content-between">
                    {{-- Gambar Menu --}}
                    <img src="{{ asset('storage/' . ($menu->image ?? 'images/default-food.jpg')) }}"
                         alt="{{ $menu->name }}" class="card-img-top rounded-t-xl" style="height: 200px; object-fit: cover;">

                    <div class="card-body flex-grow-1 p-4">
                        <h5 class="card-title font-weight-bold mb-2">{{ $menu->name }}</h5>
                        <p class="card-text text-gray-600 mb-2 truncate-text">{{ $menu->description }}</p>
                        <p class="text-primary font-weight-bold mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mb-1">
                            Kategori:
                            <span class="menu-category-display fw-bold">
                                {{-- Mengakses properti 'name' dari relasi 'category' --}}
                                {{ $menu->category->name ?? 'Belum Ada' }}
                            </span>
                        </p>
                        <p class="text-sm text-gray-500">Dibuat: {{ $menu->created_at->format('d M Y') }}</p>
                        <p class="text-sm">Status:
                            <span class="badge rounded-pill px-2 py-1 {{ $menu->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </p>
                    </div>


                    {{-- Tombol Aksi Admin --}}
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-around p-3">
                        <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-info btn-sm text-white rounded-full">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        {{-- TOMBOL TOGGLE STATUS--}}
                        <button type="button" class="btn btn-sm rounded-full {{ $menu->is_active ? 'btn-warning' : 'btn-success' }}"
                                data-bs-toggle="modal" data-bs-target="#toggleStatusModal"
                                data-name="{{ $menu->name }}"
                                data-current-status="{{ $menu->is_active ? 'aktif' : 'nonaktif' }}"
                                data-url="{{ route('admin.menu.toggle-status', $menu) }}"> {{-- Menambahkan data-url --}}
                            <i class="fas fa-toggle-{{ $menu->is_active ? 'off' : 'on' }} me-1"></i>
                            {{ $menu->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        <a href="{{ route('admin.menu.show', $menu->id) }}" class="btn btn-secondary btn-sm rounded-full">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-gray-600">Belum ada menu yang terdaftar.</p>
                <a href="{{ route('admin.menu.create') }}" class="btn btn-success rounded-lg mt-3">
                    <i class="fas fa-plus me-2"></i> Tambah Menu Pertama Anda
                </a>
            </div>
        @endforelse
    </div>

    {{-- Modal Konfirmasi Toggle Status --}}
    <div class="modal fade" id="toggleStatusModal" tabindex="-1" role="dialog" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-xl">
                <div class="modal-header text-white rounded-t-xl">
                    <h5 class="modal-title" id="toggleStatusModalLabel">Konfirmasi Perubahan Status Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin <span id="actionText" class="font-weight-bold"></span> menu "<strong id="menuNameToToggle"></strong>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-lg" data-bs-dismiss="modal">Batal</button>
                    <form id="toggleStatusForm" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn rounded-lg" id="confirmToggleButton">Konfirmasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
                        {{ $menus->links() }}
                    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk mengisi data modal toggle status
        var toggleStatusModal = document.getElementById('toggleStatusModal');
        if (toggleStatusModal) {
            toggleStatusModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget; // Tombol yang memicu modal
                
                // PERBAIKAN 2: Ambil URL dari atribut data-url
                var actionUrl = button.getAttribute('data-url');
                var menuName = button.getAttribute('data-name');
                var currentStatus = button.getAttribute('data-current-status');

                var modalHeader = toggleStatusModal.querySelector('.modal-header');
                var actionText = toggleStatusModal.querySelector('#actionText');
                var menuNameToToggle = toggleStatusModal.querySelector('#menuNameToToggle');
                var confirmToggleButton = toggleStatusModal.querySelector('#confirmToggleButton');
                var toggleStatusForm = toggleStatusModal.querySelector('#toggleStatusForm');

                // Memastikan elemen ada sebelum mengisi teks
                if (menuNameToToggle) {
                    menuNameToToggle.textContent = menuName;
                }

                // Atur teks aksi dan warna tombol/header modal
                if (currentStatus === 'aktif') {
                    if (actionText) actionText.textContent = 'menonaktifkan';
                    if (modalHeader) modalHeader.className = 'modal-header bg-warning text-white rounded-t-xl';
                    if (confirmToggleButton) confirmToggleButton.className = 'btn btn-warning rounded-lg';
                } else {
                    if (actionText) actionText.textContent = 'mengaktifkan kembali';
                    if (modalHeader) modalHeader.className = 'modal-header bg-success text-white rounded-t-xl';
                    if (confirmToggleButton) confirmToggleButton.className = 'btn btn-success rounded-lg';
                }

                // PERBAIKAN 3: Atur action form dengan URL yang sudah benar
                if (toggleStatusForm) {
                    toggleStatusForm.action = actionUrl;
                }
            });
        }

        // Logika pencarian
        var searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();
                const menuCards = document.querySelectorAll('.menu-card-col');

                menuCards.forEach(cardCol => {
                    const cardTitle = cardCol.querySelector('.card-title');
                    const cardDescription = cardCol.querySelector('.card-text');
                    const cardCategory = cardCol.querySelector('.menu-category-display');

                    const name = cardTitle ? cardTitle.innerText.toLowerCase() : '';
                    const description = cardDescription ? cardDescription.innerText.toLowerCase() : '';
                    const category = cardCategory ? cardCategory.innerText.toLowerCase() : '';
                    const statusText = cardCol.querySelector('.badge') ? cardCol.querySelector('.badge').innerText.toLowerCase() : '';

                    if (name.includes(keyword) || description.includes(keyword) || category.includes(keyword) || statusText.includes(keyword)) {
                        cardCol.style.display = 'block';
                    } else {
                        cardCol.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
<style>
    .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
