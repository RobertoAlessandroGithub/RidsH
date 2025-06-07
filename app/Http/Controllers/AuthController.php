<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


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
            if (Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/'); // redirect kalau bukan admin
        }

        return back()->with('error', 'Email atau password salah!');
    }

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
    // $user->password = bcrypt($request->password);
    $user->password = $request->password; // Laravel akan otomatis hash karena casts
    $user->role = 'user'; // optional kalau pakai role
    $user->save();

    return redirect()->route('login')->with('success', 'Berhasil daftar, silakan login!');
}

}
