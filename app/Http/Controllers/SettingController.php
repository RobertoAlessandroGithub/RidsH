<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index()
    {
        // Data pengaturan dummy, Anda bisa mengambilnya dari database atau config.
        // Nanti, kita bisa membuat tabel 'settings' untuk menyimpan ini secara dinamis.
        $settings = [
            'restaurant_name' => 'Hotel Maminko Restoran',
            'address' => 'Jalan Contoh No. 123, Palembang',
            'phone' => '+62 812-3456-7890',
            'email' => 'info@maminko.com',
            'opening_hours' => 'Senin - Minggu, 09:00 - 22:00',
        ];
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'opening_hours' => 'nullable|string|max:255',
        ]);

        // Di sini Anda akan menyimpan data ini ke database (misalnya tabel 'settings'
        // atau mengupdate config file jika diperlukan).
        // Untuk contoh ini, kita hanya akan me-redirect dengan pesan sukses.

        // Contoh: Jika ada tabel 'settings' dengan key-value pairs
        // foreach ($validatedData as $key => $value) {
        //     Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        // }

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
