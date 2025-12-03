@extends('layouts.dashboard')
@section('title', 'Daftar Quiz')

@section('content')
<div class="px-6 py-8 text-white">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold">Daftar Quiz</h2>

        <a href="{{ route('quiz.create') }}"
           class="bg-blue-600 hover:bg-blue-500 px-5 py-3 rounded-lg font-bold">
            + Tambah Quiz
        </a>
    </div>

    <div class="bg-white/10 p-6 rounded-xl shadow space-y-4">

        @if($quizzes->isEmpty())
            <p class="text-white/70">Belum ada quiz dibuat.</p>
        @else
            @foreach($quizzes as $quiz)
                <a href="{{ route('quiz.manage', $quiz->id) }}"
                   class="block bg-white/5 hover:bg-white/10 transition p-4 rounded-lg">

                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold">{{ $quiz->title }}</h3>
                            <p class="text-white/70 text-sm mt-1">{{ $quiz->description }}</p>
                        </div>

                        <span class="text-white/70 text-sm">
                            Dibuat oleh: {{ $quiz->creator->name ?? 'Unknown' }}
                        </span>
                    </div>

                </a>
            @endforeach
        @endif

    </div>
</div>
@endsection
