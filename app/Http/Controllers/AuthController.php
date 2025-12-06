<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Rank;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
	

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        // Validasi request email sama password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Logic login sederhana
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role==UserRole::STUDENT){
                return redirect()->route('dashboard.index');
            }else{
                return redirect()->route('dashboard.teacher');
            }
            
        }

        return back()->withErrors('Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken(); 

        return redirect('/')->with('success', 'Anda berhasil logout.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed',
            'role' => ['required', Rule::in([UserRole::STUDENT->value, UserRole::TEACHER->value])],
        ]);
        if ($validated['role']==UserRole::STUDENT->value)
        {
            $validated['role'] = UserRole::STUDENT;
        }
        if ($validated['role']==UserRole::TEACHER->value)
        {
            $validated['role'] = UserRole::TEACHER;
        }
        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['fullname'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        $student = null;
        $teacher = null;
        $redirectRoute = 'dashboard.student';

        if ($user->role === UserRole::TEACHER) {
            $teacher = Teacher::create([
                'user_id' => $user->id,
            ]);
            $redirectRoute = 'dashboard.teacher';
        } else {
            $rank = Rank::where('min_xp', 0)->first();
            $student = Student::create([
                'user_id' => $user->id,
                'rank_id' => $rank->id,
            ]);
        }

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Buat session baru biar aman
            return redirect()->route($redirectRoute)->with('success', 'Login berhasil!');
        }
        return response()->json([
            'message' => 'Register success, But Cant Auto Login',
            'user' => $user,
            'student' => $student,
            'teacher' => $teacher,
        ], 201);
    }
}
