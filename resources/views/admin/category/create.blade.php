    {{-- resources/views/admin/category/create.blade.php --}}

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

                            <form action="{{ route('categories.store') }}" method="POST">
                                @csrf

                            <div class="mb-3">
                            <label for="name" class="form-label">Nama Kategori:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Makanan Berat" required>
                        </div>

                               <div class="mb-3">
                                    <label for="type" class="form-label">Jenis Kategori:</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Makanan" {{ old('type') == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                                        <option value="Minuman" {{ old('type') == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                                    </select>
                                </div>

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
