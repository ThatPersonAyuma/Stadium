<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Rank;
use App\Enums\UserRole;
use App\Helpers\FileHelper;
use App\Enums\AccountStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
	

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        // Validasi request email sama password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $credentials['email'])->first();
        if ($user->role == UserRole::TEACHER)
        {
            switch ($user->teacher->status)
            {
                case AccountStatus::WAITING:
                    back()->withErrors('Akun masih diproses');
                case AccountStatus::ACCEPTED:
                    break;
                case AccountStatus::REJECTED:
                    back()->withErrors('Akun tidak diterima, silakan buat akun lain');
            }
        }
        // Logic login sederhana
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index');
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

            'role' => [
                'required',
                Rule::in([UserRole::STUDENT->value, UserRole::TEACHER->value])
            ],

            'phone_number'      => 'required_if:role,' . UserRole::TEACHER->value . '|nullable|string|max:50',
            'social_media'      => 'required_if:role,' . UserRole::TEACHER->value . '|nullable|string',
            'social_media_type' => 'required_if:role,' . UserRole::TEACHER->value . '|nullable|string',
            'institution'       => 'required_if:role,' . UserRole::TEACHER->value . '|nullable|string',
        ]);

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
                'phone_number'  => $validated['phone_number'],
                'social_media'  => $validated['social_media'],
                'social_media_type' =>$validated['social_media_type'] ,
                'institution'   =>$validated['institution'],
                'status'        =>AccountStatus::WAITING,
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
    public function UpdateProfile(Request $request)
    {
        $user = auth()->user();
        Log::info('exist file: ', $request->all());
        $validated = $request->validate([
            'fullname' => 'required|string',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore(auth()->id()),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(auth()->id()),
            ],
            'password' => 'nullable|confirmed',
            'avatar' => 'nullable|image|max:2048', // max 2MB

            'role' => [
                'required',
                Rule::in([UserRole::STUDENT->value, UserRole::TEACHER->value])
            ],

            'phone_number' => [
                Rule::requiredIf($request->role === UserRole::TEACHER->value),
                'string',
                'max:50'
            ],
            'social_media' => [
                Rule::requiredIf($request->role === UserRole::TEACHER->value),
                'string',
            ],
            'social_media_type' => [
                Rule::requiredIf($request->role === UserRole::TEACHER->value),
                'string',
            ],
            'institution' => [
                Rule::requiredIf($request->role === UserRole::TEACHER->value),
                'string',
            ],
        ]);
        
        // Update avatar jika ada file
        // Log::info('exist file: ', $request->hasFile('avatar'));
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // Hapus file lama jika ada
            if ($user->avatar_filename && file_exists(public_path($user->avatar_filename))) {
                unlink(public_path($user->avatar_filename));
            }
            
            // Nama file baru
            $result = FileHelper::storeAvatarFile($file, $user->id);
            Log::info('link: '. $result);
        }

        // Update field biasa
        $user->name = $validated['fullname'];
        $user->email = $validated['email'];
        $user->username = $validated['username'] ?? $user->username;

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        switch($validated['role'])
        {
            case UserRole::STUDENT->value:
                break;
            case UserRole::TEACHER->value:
                $teacher = $user->teacher;
                $teacher->phone_number = $validated['phone_number'];
                $teacher->social_media = $validated['social_media'];
                $teacher->social_media_type = $validated['social_media_type'];
                $teacher->institution = $validated['institution'];
                $teacher->save();
                break;
        }

        return back()->with('status', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Profil berhasil diperbarui.'
        ]);
    }
    public function ProfileIndex()
    {
        $user = Auth::user();
        switch ($user->role)
            {
                case UserRole::STUDENT:
                    return view('profile.student');
                case UserRole::TEACHER:
                    return view('profile.teacher');
                case UserRole::ADMIN:
                    back()->withErrors('Akun tidak diterima, silakan buat akun lain');
            }
    }
}
