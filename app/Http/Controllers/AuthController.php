<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Pastikan ini ada

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $role = Auth::user()->role;
            Log::info('Pengguna mencoba login. Role terdeteksi: ' . $role);

            // Menggunakan trim() untuk membersihkan spasi yang tidak terlihat
            if (trim($role) === 'admin') {
                Log::info('Kondisi terpenuhi. Mengarahkan ke admin/dashboard.');
                return redirect('/admin/dashboard');
            }

            Log::info('Kondisi GAGAL. Mengarahkan ke halaman utama.');
            return redirect('/');
        }

        Log::warning('Login gagal untuk email: ' . $request->email);
        return back()->with('error', 'Email atau password salah!');
    } // <--- INI KURUNG KURAWAL YANG KEMUNGKINAN HILANG

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = 'user';
        $user->save();

        return redirect()->route('login')->with('success', 'Berhasil daftar, silakan login!');
    }
}
