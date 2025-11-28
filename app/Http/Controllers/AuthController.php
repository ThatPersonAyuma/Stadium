<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
	

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
            if (Auth::user()->role==UserRole::STUDENT){
                return redirect()->route('dashboard.student');
            }else{
                return redirect()->route('dashboard.teacher');
            }
            
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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required',
        ]);
        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['fullname'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::STUDENT,
        ]);

        // 2. Create student (ambil user_id dari $user->id)
        $student = Student::create([
            'user_id' => $user->id,
            
        ]);
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Buat session baru biar aman
            return redirect()->route('student.dashboard')->with('success', 'Login berhasil!');
        }
        return response()->json([
            'message' => 'Register success, But Cant Auto Login',
            'user' => $user,
            'student' => $student,
        ], 201);
    }
}
