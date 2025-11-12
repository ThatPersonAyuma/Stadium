<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login buat autentikasi
    public function login(Request $request) 
    {
        // Validasi request email sama password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Logic login sederhana
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Buat session baru biar aman
            return redirect()->route('welcome')->with('success', 'Login berhasil!');
        }

        return back()->withErrors('Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Hapus autentikasi user
        $request->session()->invalidate(); // Hapus semua session
        $request->session()->regenerateToken(); // Regenerasi CSRF token biar aman

        return redirect('/')->with('success', 'Anda berhasil logout.');
    }
}
