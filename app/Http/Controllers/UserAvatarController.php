<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserAvatarController extends Controller
{
    public static function storeAvatarFile($file, $userId)
    {
        $user = User::findOrFail($userId);
        $filename = "{$user->id}-{$user->avatar_filename}";

        $stored = Storage::disk('public')->putFileAs(
            dirname('avatar/a'),
            $file,
            $filename
        );

        return 'storage/' . $stored;
    }

    public static function getAvatarPath($userId)
    {
        $user = User::findOrFail($userId);
        return 'storage/avatar/' . "{$user->id}-{$user->avatar_filename}";
    }

    // --- Tampilkan form upload avatar ---
    public function showForm(Request $request)
    {
        // Validasi ID user
        $validated = $request->validate([
            'user_id' => 'required|integer',
        ]);
        
        $user = User::findOrFail($validated['user_id']);
        $avatarPath = null;
        
        // Cek apakah user punya filename dan file-nya ada di storage
        if (!empty($user->avatar_filename)) {
            $expectedFile = "avatar/{$user->id}-{$user->avatar_filename}";

            if (Storage::disk('public')->exists($expectedFile)) {
                $avatarPath = 'storage/' . $expectedFile;
            }
        }

        return view('avatar', compact('user', 'avatarPath'));
    }


    // --- Proses upload avatar ---
    public function upload(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->avatar_filename = $request->file('avatar')->getClientOriginalName();
        $user->save();

        $path = self::storeAvatarFile($request->file('avatar'), $user->id);

        return redirect()->route('avatar.form')
                         ->with('success', "Avatar berhasil diunggah! ($path)");
    }
}
