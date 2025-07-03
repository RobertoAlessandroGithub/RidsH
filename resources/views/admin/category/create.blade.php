@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow rounded-xl">
                    <div class="card-header bg-primary text-white rounded-t-xl">
                        <h1 class="h4 mb-0">Tambah Kategori Baru</h1>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Kategori:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Makanan Berat" required>
                            </div>

                            {{-- =================================================== --}}
                            {{-- PERBAIKAN DI SINI: Field "Jenis Kategori" dihapus --}}
                            {{-- =================================================== --}}

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Simpan Kategori</button>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-lg">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
