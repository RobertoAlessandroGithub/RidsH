<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the menus for admin.
     */
    public function index()
    {
        // Eager load relasi 'category' untuk menghindari N+1 query problem
        $menus = Menu::with('category')->latest()->paginate(12); // Menggunakan paginate untuk performa
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get(); // Ambil semua kategori
        return view('admin.menu.create', compact('categories'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        // ===================================================
        // PERBAIKAN DI SINI
        // ===================================================
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            // Validasi sekarang memeriksa apakah ID kategori ada di tabel 'categories'
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_images', 'public');
        }

        Menu::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Display the specified menu (for admin detail view).
     */
    public function show(Menu $menu)
    {
         $menu->load('category');
        return view('admin.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = $menu->image;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('menu_images', 'public');
        }

        $menu->update([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']),
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Toggle the active status of the specified menu.
     */
    public function toggleActiveStatus(Menu $menu)
    {
        $menu->is_active = !$menu->is_active;
        $menu->save();
        return back()->with('success', 'Status menu berhasil diperbarui!');
    }

    // ... (Fungsi untuk tampilan pelanggan lainnya) ...

    public function maminkoIndex(Request $request)
    {
        $query = Menu::where('is_active', true);

        if ($request->has('category') && $request->category != 'all') {
            $categorySlug = $request->category;
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $menus = $query->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('customer.maminko.maminko', compact('menus', 'categories'));
    }

    public function showDetail($slug)
    {
        $menu = Menu::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('customer.maminko.menu-detail', compact('menu'));
    }
}
