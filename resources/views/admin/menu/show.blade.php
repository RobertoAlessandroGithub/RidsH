{{-- resources/views/admin/menu/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Detail Menu - ' . ($menu->name ?? 'N/A'))

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Detail Item Menu: {{ $menu->name ?? 'N/A' }}</h1>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4 rounded-xl">
                <div class="card-header py-3 bg-info text-white rounded-t-xl">
                    <h6 class="m-0 font-weight-bold">Informasi Menu</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">ID Menu:</div>
                        <div class="col-md-8">{{ $menu->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Nama Menu:</div>
                        <div class="col-md-8">{{ $menu->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Deskripsi Singkat:</div>
                        <div class="col-md-8">{{ $menu->description }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Harga:</div>
                        <div class="col-md-8">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Kategori:</div>
                        <div class="col-md-8">{{ $menu->category ?? 'Belum Ada' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Status:</div>
                        <div class="col-md-8">
                            <span class="badge rounded-pill px-2 py-1 {{ $menu->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Tanggal Dibuat:</div>
                        <div class="col-md-8">{{ $menu->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Terakhir Diperbarui:</div>
                        <div class="col-md-8">{{ $menu->updated_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4 rounded-xl">
                <div class="card-header py-3 bg-info text-white rounded-t-xl">
                    <h6 class="m-0 font-weight-bold">Gambar Menu</h6>
                </div>
                <div class="card-body text-center">
                    @if ($menu->image)
                        <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="img-fluid rounded-lg mb-3" style="max-height: 250px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-food.jpg') }}" alt="Default Food" class="img-fluid rounded-lg mb-3" style="max-height: 250px; object-fit: cover;">
                        <p class="text-muted">Tidak ada gambar yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Aksi --}}
    <div class="d-flex justify-content-start mt-4">
        <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-info rounded-lg me-2">
            <i class="fas fa-edit me-1"></i> Edit Menu Ini
        </a>
        <a href="{{ route('menu.index') }}" class="btn btn-secondary rounded-lg">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Menu
        </a>
    </div>
@endsection
