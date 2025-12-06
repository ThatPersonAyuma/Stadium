@extends('layouts.dashboard')
@section('title', 'Tambah Quiz')

@section('content')
<div class="px-6 py-8 text-white max-w-3xl mx-auto">

    <h2 class="text-3xl font-bold mb-6">Tambah Quiz Baru</h2>

    <form action="{{ route('quiz.update') }}" method="POST"
          class="bg-white/10 p-6 rounded-xl space-y-6">
        @csrf

        <div>
            <label class="font-semibold">Judul Quiz</label>
            <input type="text" name="title" class="w-full mt-2 p-3 bg-white/5 rounded-lg" required>
        </div>

        <div>
            <label class="font-semibold">Deskripsi</label>
            <textarea name="description" class="w-full mt-2 p-3 bg-white/5 rounded-lg"></textarea>
        </div>

        <button class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg font-bold w-full">
            Simpan Quiz
        </button>
    </form>

</div>
@endsection
