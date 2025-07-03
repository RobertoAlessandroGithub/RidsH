{{-- resources/views/admin/menu/edit.blade.php --}}

@extends('layouts.admin') {{-- HARUS ADA DI BARIS PERTAMA DAN TIDAK ADA APA PUN DI ATASNYA --}}

@section('title', 'Edit Menu - ' . ($menu->name ?? 'N/A')) {{-- Judul dinamis --}}

@section('content') {{-- MULAI SEKSI KONTEN UTAMA --}}
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-info text-white rounded-t-xl">
                        <h1 class="h4 mb-0">Edit Menu: {{ $menu->name ?? 'N/A' }}</h1>
                    </div>
                    <div class="card-body">
                        {{-- Notifikasi Sukses/Error/Validasi --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-lg" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
                                <strong>Ada beberapa kesalahan:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Menu:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi:</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $menu->description) }}</textarea>
                            </div>

                            {{-- Field untuk deskripsi detail DIHAPUS --}}

                            <div class="mb-3">
                                <label for="price" class="form-label">Harga:</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $menu->price) }}" min="0" step="500" required>
                                </div>
                            </div>

                            {{-- Preview Gambar Saat Ini --}}
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini:</label>
                                @if ($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="Gambar {{ $menu->name }}" class="current-image-preview img-thumbnail">
                                    <p class="text-muted text-sm mt-1">Biarkan kosong untuk tidak mengubah gambar.</p>
                                @else
                                    <p class="text-muted">Tidak ada gambar saat ini.</p>
                                @endif
                                <label for="image" class="form-label mt-2">Ganti Gambar Menu:</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <small class="text-muted">Upload gambar baru (format: jpg, png, jpeg, maks 2MB).</small>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Kategori (Opsional):</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info">Perbarui Menu</button>
                                <a href="{{ route('menu.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection {{-- AKHIR SEKSI KONTEN UTAMA --}}

@push('scripts') {{-- Jika ada JS spesifik untuk halaman ini --}}
<script>
    // Styling khusus untuk current-image-preview
    document.addEventListener('DOMContentLoaded', function() {
        const style = document.createElement('style');
        style.innerHTML = `
            .current-image-preview {
                max-width: 150px;
                height: auto;
                border-radius: 0.5rem;
                margin-top: 0.5rem;
                border: 1px solid #ddd;
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush
