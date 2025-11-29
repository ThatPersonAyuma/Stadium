@extends('layouts.main')

@section('title', 'Choose Role')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">

    <h1 class="text-4xl font-bold text-[#EDB240] mb-3">Choose Your Role</h1>
    <p class="text-white/90 mb-10 text-center">Select how you want to join Stadium</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl">

        {{-- Student Card --}}
        <div class="bg-white shadow-lg rounded-2xl p-8 border hover:shadow-xl transition">
            <h2 class="text-2xl font-bold mb-2 text-[#002872]">Student</h2>
            <p class="text-gray-600 mb-6">Belajar course interaktif dan kumpulkan EXP.</p>

            <ul class="space-y-3 text-gray-700 mb-8">
                <li class="flex items-center gap-2">- Akses semua course publik</li>
                <li class="flex items-center gap-2">- Ikut quiz & tournament</li>
                <li class="flex items-center gap-2">- Dapat EXP, badge, dan reward tanaman</li>
                <li class="flex items-center gap-2">- Dashboard personal</li>
            </ul>

            <a href="{{ route('register.student') }}"
               class="block w-full text-center bg-[#EDB240] hover:bg-[#d9a237] text-white font-semibold py-3 rounded-xl">
                Join as Student
            </a>
        </div>

        {{-- Teacher Card --}}
        <div class="bg-white shadow-lg rounded-2xl p-8 border hover:shadow-xl transition">
            <h2 class="text-2xl font-bold mb-2 text-[#002872]">Teacher</h2>
            <p class="text-gray-600 mb-6">Buat materi pembelajaran dan kelola course.</p>

            <ul class="space-y-3 text-gray-700 mb-8">
                <li class="flex items-center gap-2">- Buat dan kelola course</li>
                <li class="flex items-center gap-2">- Upload materi & quiz</li>
                <li class="flex items-center gap-2">- Pantau progress student</li>
                <li class="flex items-center gap-2">- Sistem verifikasi admin</li>
            </ul>

            <a href="{{ route('register.teacher') }}"
               class="block w-full text-center bg-[#002872] hover:bg-[#001e5c] text-white font-semibold py-3 rounded-xl">
                Become a Teacher
            </a>
        </div>

    </div>
</div>
@endsection
