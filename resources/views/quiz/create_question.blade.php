@extends('layouts.dashboard')
@section('title', 'Tambah Pertanyaan')

@section('content')
<div class="px-6 py-8 text-white max-w-3xl mx-auto">

    <x-dashboard-header title="Tambah Pertanyaan untuk: {{ $quiz->title }}" />

    <form action="{{ route('quiz.question.store', $quiz->id) }}" method="POST"
          class="bg-white/10 p-6 rounded-xl space-y-6">
        @csrf

        <div>
            <label class="font-semibold">Pertanyaan</label>
            <textarea name="question" class="w-full mt-2 p-3 bg-white/5 rounded-lg" required></textarea>
        </div>

        <div>
            <label class="font-semibold">Urutan Pertanyaan</label>
            <input type="number" name="order_index" class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ $nextNumber }}" required>
        </div>

        <div class="space-y-4">
            <h3 class="text-xl font-bold">Pilihan Jawaban</h3>

            @foreach(['A','B','C','D'] as $label)
            <div class="bg-white/5 p-4 rounded-lg">
                <label class="font-bold">{{ $label }}.</label>
                <input type="text" name="choices[{{ $label }}][text]"
                       class="w-full mt-2 p-2 bg-white/10 rounded"
                       placeholder="Teks pilihan" required>

                <div class="mt-2 flex items-center gap-2">
                    <input type="radio" name="correct" value="{{ $label }}" required>
                    <span class="text-sm">Tandai sebagai jawaban benar</span>
                </div>
            </div>
            @endforeach
        </div>

        <button class="bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg font-bold w-full">
            Simpan Pertanyaan
        </button>
    </form>
</div>
@endsection
