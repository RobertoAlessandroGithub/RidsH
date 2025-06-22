<?php

namespace App\Http\Controllers;

use App\Models\Menu; // Pastikan Model Menu sudah ada dan benar
use Illuminate\Http\Request;
use App\Models\Category; // Jika Anda memang menggunakan Model Category, biarkan. Jika tidak, bisa dihapus.
use Illuminate\Support\Facades\Storage; // Ini sudah benar

class MenuController extends Controller
{
    /**
     * Display a listing of the resource. (Biasanya untuk halaman admin daftar semua menu)
     * URL: /menu (GET)
     */
    public function index()
    {
        $menus = \App\Models\Menu::all();
        return view('admin.menu.index', compact('menus')); // Ubah dari 'index' menjadi 'menu-card'
    }

    // app/Http/Controllers/MenuController.php

// ... (bagian atas)

    // ... (metode lainnya: create, store, show, edit, update, destroy)

    /**
     * Show the form for creating a new resource.
     * URL: /menu/create (GET)
     */
    public function create()
    {
        // SESUAIKAN PATH VIEW INI! Sesuai nama file create-menu.blade.php
        // Asumsi: resources/views/admin/menu/create-menu.blade.php
        return view('admin.menu.create');
    }

    /**
     * Store a newly created resource in storage.
     * URL: /menu (POST)
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang masuk dari form
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:menus,name',
                'description' => 'required|string',
                'detailed_description' => 'nullable|string', // Pastikan kolom ini ada di DB atau hapus
                'price' => 'required|numeric|min:0',
                'category' => 'nullable|string|max:255', // Pastikan kolom ini ada di DB atau hapus
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle upload gambar jika ada
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu_images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                $validatedData['image'] = null; // Set null jika tidak ada gambar diupload
            }

            // Simpan data menu ke database
            Menu::create($validatedData);

            // Redirect ke halaman daftar menu dengan pesan sukses
            return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return back()->withInput()->with('error', 'Gagal menyimpan menu: '.$e->getMessage());
        }
    }

    // Tambahkan method lain (show, edit, update, destroy, maminkoIndex, showDetail) di sini nanti
    // Untuk saat ini, kita fokus pada create dan store
    /**
     * Display the specified resource. (Biasanya untuk halaman admin detail satu menu)
     * URL: /menu/{menu} (GET)
     */
    public function show(Menu $menu)
    {
        // Asumsi: resources/views/admin/menu/show.blade.php
        return view('admin.menu.show', compact('menu')); // SESUAIKAN PATH VIEW INI jika berbeda!
    }

    /**
     * Show the form for editing the specified resource.
     * URL: /menu/{menu}/edit (GET)
     */
    public function edit(Menu $menu)
    {
        // Asumsi: resources/views/admin/menu/edit.blade.php
        return view('admin.menu.edit', compact('menu')); // SESUAIKAN PATH VIEW INI jika berbeda!
    }

    /**
     * Update the specified resource in storage.
     * URL: /menu/{menu} (PUT/PATCH)
     */
    public function update(Request $request, Menu $menu)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:menus,name,' . $menu->id, // unique:menus,name,ID_menu_saat_ini
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'detailed_description' => 'nullable|string', // Tambahkan validasi jika field ini ada
                'category' => 'nullable|string|max:255', // Tambahkan validasi jika field ini ada
            ]);

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                    Storage::disk('public')->delete($menu->image);
                }
                // Simpan gambar baru
                $imagePath = $request->file('image')->store('menu_images', 'public');
                $validatedData['image'] = $imagePath;
            } else {
                // Jika tidak ada gambar baru di-upload, pertahankan gambar lama
                $validatedData['image'] = $menu->image;
            }

            $menu->update($validatedData);

            return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui menu: '.$e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     * URL: /menu/{menu} (DELETE)
     */
    public function destroy(Menu $menu)
    {
        try {
            // Hapus gambar dari storage jika ada
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }

            $menu->delete();
            return redirect()->route('menu.index')->with('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus menu: '.$e->getMessage());
        }
    }

    /**
     * Menampilkan view untuk menghapus menu (biasanya admin view terpisah)
     * URL: /admin/menu/delete-view (GET)
     */
    public function deleteView()
    {
        $menus = Menu::latest()->get();
        // Asumsi: resources/views/admin/menu/delete.blade.php
        return view('admin.menu.delete', compact('menus')); // SESUAIKAN PATH VIEW INI jika berbeda!
    }

    // =====================================================================
    // Method untuk Halaman MAMINKO (customer view)
    // =====================================================================

    /**
     * Menampilkan daftar menu di halaman Maminko (untuk customer).
     * URL: /maminko (GET)
     */
    public function maminkoIndex()
    {
        $menus = Menu::all(); // Ambil semua menu dari database
        return view('customer.maminko.maminko', compact('menus'));
    }

    /**
     * Menampilkan detail satu menu di halaman Maminko (untuk customer).
     * URL: /menu/{name} (GET)
     *
     * @param string $name
     * @return \Illuminate\View\View
     */
    public function showDetail($name)
    {
        // Mencari menu di database berdasarkan 'name'
        // 'firstOrFail()' akan otomatis melempar 404 jika menu tidak ditemukan
        $menu = Menu::where('name', urldecode($name))->firstOrFail();

        // Ambil juga beberapa menu rekomendasi dari database
        // Contoh: Ambil 4 menu acak yang berbeda dari menu saat ini
        $recommendedMenus = Menu::where('id', '!=', $menu->id)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();

        return view('customer.maminko.menu-detail', compact('menu', 'recommendedMenus'));
    }
}
