<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Sebaiknya gunakan paginasi untuk performa jika data banyak
        $categories = Category::latest()->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        try {
            // Slug akan otomatis dibuat oleh mutator di model (jika ada)
            // atau bisa dibuat di sini jika tidak ada mutator.
            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name) // Tambahkan slug secara eksplisit
            ]);

            // PERBAIKAN: Menggunakan nama rute yang benar
            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Kategori berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: '.$e->getMessage());
        }
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);
        $category->update($validatedData);

        // PERBAIKAN: Menggunakan nama rute yang benar
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            // PERBAIKAN: Menggunakan nama rute yang benar
            return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
        } catch (\Exception $e) {
            // Pesan error ini sudah bagus karena informatif
            return back()->with('error', 'Gagal menghapus kategori. Pastikan tidak ada menu yang menggunakan kategori ini.');
        }
    }
}
