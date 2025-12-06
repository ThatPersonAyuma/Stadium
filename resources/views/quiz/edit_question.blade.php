@extends('layouts.dashboard')
@section('title', 'Edit Pertanyaan')

@section('content')
<div class="px-6 py-8 text-white max-w-3xl mx-auto">

    <x-dashboard-header title="Edit Pertanyaan" />

    <form action="{{ route('quiz.question.update', [ $question->id,]) }}"
          method="POST" class="bg-white/10 p-6 rounded-xl space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="font-semibold">Pertanyaan</label>
            <textarea name="question" class="w-full mt-2 p-3 bg-white/5 rounded-lg" required>
                {{ $question->question }}
            </textarea>
        </div>

        <div>
            <label class="font-semibold">Urutan</label>
            <input type="number" name="order_index" class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                   value="{{ $question->order_index }}" required>
        </div>

        <div class="space-y-4">
            <h3 class="text-xl font-bold">Pilihan Jawaban</h3>

            @foreach($question->choices as $c)
            <div class="bg-white/5 p-4 rounded-lg">
                <label class="font-bold">{{ $c->label }}.</label>
                <input type="text" name="choices[{{ $c->label }}][text]"
                       class="w-full mt-2 p-2 bg-white/10 rounded"
                       value="{{ $c->text }}" required>

                <div class="mt-2 flex items-center gap-2">
                    <input type="radio" name="correct"
                           value="{{ $c->label }}" {{ $c->is_correct ? 'checked' : '' }}>
                    <span class="text-sm">Jawaban benar</span>
                </div>
            </div>
            @endforeach
        </div>

        <button class="bg-yellow-600 hover:bg-yellow-500 px-6 py-3 rounded-lg font-bold w-full">
            Update Pertanyaan
        </button>
    </form>

</div>
@endsection
