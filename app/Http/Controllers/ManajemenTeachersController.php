<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 

class ManajemenTeachersController extends Controller
{
    public function index()
    {

        $teachers = User::where('role', 'teacher')->latest()->get();


        return view('admin.manajemen-teachers', compact('teachers'));
    }
}