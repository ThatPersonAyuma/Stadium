<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Teacher; 
use App\Enums\AccountStatus; 
use Illuminate\Support\Facades\Log;

class ManajemenTeachersController extends Controller
{
    public function index()
    {

        $teachers = User::where('role', 'teacher')->latest()->get()->load('teacher');

        Log::info($teachers);
        return view('admin.manajemen-teachers', compact('teachers'));
    }
    public function action(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|integer',
            'action'     => 'required|in:accepted,rejected'
        ]);

        $teacher = Teacher::find($request['teacher_id']);
        if ($request->action === 'accepted') {
            $teacher->status = AccountStatus::ACCEPTED;
        } else {
            $teacher->status = AccountStatus::REJECTED;
        }
        $teacher->save();

        return back()->with('success', 'Status guru berhasil diperbarui!');
    }
}