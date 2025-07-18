{{-- Menggunakan layout admin yang sudah kita buat sebelumnya --}}
@extends('layouts.admin')

@section('title', 'Kelola Menu Restoran')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Manajemen Item Menu</h1>

    {{-- Notifikasi Sukses/Error (sama seperti di dashboard) --}}
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
        <a href="{{ route('menu.create') }}" class="btn btn-primary rounded-lg shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Menu Baru
        </a>
    </div>

    {{-- Konten Daftar Menu --}}
    <div class="menu-container row" id="menuList">
        @forelse($menus as $menu)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow rounded-xl h-100 d-flex flex-column justify-content-between">
                    {{-- Gambar Menu --}}
                    {{-- Pastikan asset() mengarah ke public/storage/nama_file_gambar.jpg --}}
                    <img src="{{ asset('storage/' . ($menu->image ?? 'images/default-food.jpg')) }}"
                         alt="{{ $menu->name }}" class="card-img-top rounded-t-xl" style="height: 200px; object-fit: cover;">

                    <div class="card-body flex-grow-1 p-4">
                        <h5 class="card-title font-weight-bold mb-2">{{ $menu->name }}</h5>
                        <p class="card-text text-gray-600 mb-2 truncate-text">{{ $menu->description }}</p>
                        <p class="text-primary font-weight-bold mb-2">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500 mb-1">Kategori: {{ $menu->category ?? 'Belum Ada' }}</p> {{-- Tambahkan kategori jika ada di DB --}}
                        <p class="text-sm text-gray-500">Dibuat: {{ $menu->created_at->format('d M Y') }}</p>
                    </div>

                    {{-- Tombol Aksi Admin --}}
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-around p-3">
                        <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-info btn-sm text-white rounded-full">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm rounded-full" data-bs-toggle="modal" data-bs-target="#deleteMenuModal" data-id="{{ $menu->id }}" data-name="{{ $menu->name }}">
                            <i class="fas fa-trash-alt me-1"></i> Hapus
                        </button>
                        <a href="{{ route('menu.show', $menu->id) }}" class="btn btn-secondary btn-sm rounded-full">
                            <i class="fas fa-eye me-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-gray-600">Belum ada menu yang terdaftar.</p>
                <a href="{{ route('menu.create') }}" class="btn btn-success rounded-lg mt-3">
                    <i class="fas fa-plus me-2"></i> Tambah Menu Pertama Anda
                </a>
            </div>
        @endforelse
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="deleteMenuModal" tabindex="-1" role="dialog" aria-labelledby="deleteMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-xl">
                <div class="modal-header bg-danger text-white rounded-t-xl">
                    <h5 class="modal-title" id="deleteMenuModalLabel">Konfirmasi Hapus Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus menu "<strong id="menuNameToDelete"></strong>"?
                    Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-lg" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-lg">Hapus Permanen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Inisialisasi AOS (jika digunakan)
    AOS.init();

    // Logika untuk mengisi data modal hapus
    document.addEventListener('DOMContentLoaded', function() {
        var deleteMenuModal = document.getElementById('deleteMenuModal');
        deleteMenuModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Tombol yang memicu modal
            var menuId = button.getAttribute('data-id');
            var menuName = button.getAttribute('data-name');

            var modalTitle = deleteMenuModal.querySelector('.modal-title');
            var modalBodyInput = deleteMenuModal.querySelector('#menuNameToDelete');
            var deleteForm = deleteMenuModal.querySelector('#deleteForm');

            modalBodyInput.textContent = menuName;
            deleteForm.action = '/menu/' + menuId; // Sesuaikan dengan route DELETE Anda
        });

        // Logika pencarian
        document.getElementById('searchInput').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const menuContainer = document.getElementById('menuList');
            const menuCards = menuContainer.querySelectorAll('.col-lg-4.col-md-6.mb-4'); // Seleksi setiap kolom kartu

            menuCards.forEach(cardCol => { // Iterasi melalui kolom, bukan langsung kartu
                const card = cardCol.querySelector('.card'); // Dapatkan kartu di dalam kolom
                if (card) {
                    const name = card.querySelector('.card-title').innerText.toLowerCase();
                    const description = card.querySelector('.card-text').innerText.toLowerCase();

                    if (name.includes(keyword) || description.includes(keyword)) {
                        cardCol.style.display = 'block'; // Tampilkan kolom
                    } else {
                        cardCol.style.display = 'none'; // Sembunyikan kolom
                    }
                }
            });
        });
    });
</script>
<style>
    /* Tambahan Style untuk Truncate Text */
    .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
