    {{-- resources/views/admin/category/edit.blade.php --}}

    @extends('layouts.admin')

    @section('title', 'Edit Kategori - ' . ($category->name ?? 'N/A'))

    @section('content')
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card shadow rounded-xl">
                        <div class="card-header bg-info text-white rounded-t-xl">
                            <h1 class="h4 mb-0">Edit Kategori: {{ $category->name ?? 'N/A' }}</h1>
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

                            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kategori:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                </div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-info btn-lg">Perbarui Kategori</button>
                                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-lg">Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
