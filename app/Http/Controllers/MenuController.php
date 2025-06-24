<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category; // Import model Category
use App\Models\Order; // Import Order model
use App\Models\OrderItem; // Import OrderItem model
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk slug
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar
use Illuminate\Validation\ValidationException; // Untuk error validasi
use Illuminate\Support\Facades\DB; // Untuk transaksi database

class MenuController extends Controller
{
    /**
     * Display a listing of the menus for admin.
     */
    public function index()
    {
        $menus = Menu::with('category')->orderBy('created_at', 'desc')->get();
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori
        return view('admin.menu.create', compact('categories'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'category_id' => 'required|exists:categories,id', // Validasi category_id
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_images', 'public');
        }

        Menu::create([
            'name' => $validatedData['name'],
            'slug' => Str::slug($validatedData['name']), // Generate slug from name
            'category_id' => $validatedData['category_id'],
            'description' => $validatedData['description'],
            'price' => $validatedData['price'],
            'image' => $imagePath,
            'is_active' => true, // Default to active
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Display the specified menu (for admin detail view).
     */
    public function show(Menu $menu)
    {
        // This 'show' is for admin panel to see menu details,
        // it's different from the public 'showDetail' method.
        return view('admin.menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $menu->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
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
            // 'is_active' tidak diupdate di sini, diupdate di toggleActiveStatus
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui!');
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
        return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus!');
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


    // =======================================================================
    // FUNGSI UNTUK TAMPILAN PELANGGAN (MaMinKo)
    // =======================================================================

    /**
     * Display a listing of the menus for the customer (MaMinKo page).
     */
    public function maminkoIndex(Request $request)
    {
        $query = Menu::where('is_active', true);

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != 'all') {
            $categorySlug = $request->category;
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter berdasarkan pencarian nama menu
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $menus = $query->with('category')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get(); // Ambil semua kategori untuk filter

        return view('customer.maminko.maminko', compact('menus', 'categories'));
    }


    /**
     * Display the specified menu details for the customer.
     */
    public function showDetail($slug)
{
    $menu = Menu::where('slug', $slug)->where('is_active', true)->firstOrFail();
    return view('customer.maminko.menu-detail', compact('menu'));
}
}
