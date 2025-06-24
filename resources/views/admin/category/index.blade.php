    {{-- resources/views/admin/category/index.blade.php --}}

    @extends('layouts.admin')

    @section('title', 'Manajemen Kategori Menu')

    @section('content')
        <h1 class="h3 mb-4 text-gray-800">Manajemen Kategori Menu</h1>

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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Daftar Kategori</h4>
            <a href="{{ route('categories.create') }}" class="btn btn-primary rounded-lg shadow-sm">
                <i class="fas fa-plus me-2"></i> Tambah Kategori Baru
            </a>
        </div>

        <div class="card shadow mb-4 rounded-xl">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th>Dibuat Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ $category->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-info btn-sm text-white rounded-full me-2">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm rounded-full" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada kategori yang tersedia.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modal Konfirmasi Hapus Kategori --}}
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-xl">
                    <div class="modal-header bg-danger text-white rounded-t-xl">
                        <h5 class="modal-title" id="deleteCategoryModalLabel">Konfirmasi Hapus Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus kategori "<strong id="categoryNameToDelete"></strong>"?
                        Tindakan ini tidak dapat dibatalkan dan mungkin memengaruhi menu yang menggunakannya.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-lg" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteCategoryForm" method="POST" action="">
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
        document.addEventListener('DOMContentLoaded', function() {
            var deleteCategoryModal = document.getElementById('deleteCategoryModal');
            if (deleteCategoryModal) {
                deleteCategoryModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var categoryId = button.getAttribute('data-id');
                    var categoryName = button.getAttribute('data-name');

                    var modalBodyInput = deleteCategoryModal.querySelector('#categoryNameToDelete');
                    var deleteForm = deleteCategoryModal.querySelector('#deleteCategoryForm');

                    if (modalBodyInput) {
                        modalBodyInput.textContent = categoryName;
                    }
                    if (deleteForm) {
                        deleteForm.action = `/categories/${categoryId}`; // URL untuk hapus kategori
                    }
                });
            }
        });
    </script>
    @endpush
