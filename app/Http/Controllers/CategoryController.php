<?php

    namespace App\Http\Controllers;

    use App\Models\Category;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str; // Untuk membuat slug

    class CategoryController extends Controller
    {
        /**
         * Display a listing of the categories.
         */
        public function index()
        {
            $categories = Category::all();
            return view('admin.category.index', compact('categories'));
        }

        /**
         * Show the form for creating a new category.
         */
        public function create()
        {
            return view('admin.category.create');
        }

        /**
         * Store a newly created category in storage.
         */
        public function store(Request $request)
        {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
            ]);

            $validatedData['slug'] = Str::slug($validatedData['name']);

            Category::create($validatedData);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
        }

        /**
         * Show the form for editing the specified category.
         */
        public function edit(Category $category)
        {
            return view('admin.category.edit', compact('category'));
        }

        /**
         * Update the specified category in storage.
         */
        public function update(Request $request, Category $category)
        {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            ]);

            $validatedData['slug'] = Str::slug($validatedData['name']);

            $category->update($validatedData);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
        }

        /**
         * Remove the specified category from storage.
         */
        public function destroy(Category $category)
        {
            try {
                // Perhatian: Ini akan menghapus kategori secara permanen.
                // Jika ada menu yang menggunakan kategori ini, Anda perlu menangani relasinya terlebih dahulu.
                // Misalnya, set kategori menu menjadi null atau default sebelum menghapus kategori.

                $category->delete();
                return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
            }
        }
    }
