{{-- resources/views/admin/settings/index.blade.php --}}

@extends('layouts.admin')

@section('title', 'Pengaturan Aplikasi')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Aplikasi</h1>

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
        <div class="card-header py-3 bg-gray-50 rounded-t-xl">
            <h6 class="m-0 font-weight-bold text-primary">Pengaturan Umum Restoran</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="restaurant_name" class="form-label">Nama Restoran:</label>
                    <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="{{ old('restaurant_name', $settings['restaurant_name'] ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat:</label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $settings['address'] ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon:</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="opening_hours" class="form-label">Jam Buka:</label>
                    <input type="text" class="form-control" id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours'] ?? '') }}" placeholder="Contoh: Senin - Minggu, 09:00 - 22:00">
                </div>

                <button type="submit" class="btn btn-primary rounded-lg mt-3">Simpan Pengaturan</button>
            </form>
        </div>
    </div>
@endsection
