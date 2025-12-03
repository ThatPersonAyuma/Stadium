@extends('layouts.dashboard')
@section('title', "Manage Quiz: $quiz->title")

@section('content')
<div class="px-6 py-8 text-white">

    <x-dashboard-header :title="'Kelola Pertanyaan – ' . $quiz->title" />

    <div class="bg-white/10 p-6 rounded-xl shadow">
        <div class="flex justify-between mb-4">
            <h3 class="text-2xl font-bold">Daftar Pertanyaan</h3>
            <a href="{{ route('quiz.question.create', $quiz->id) }}"
               class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg font-semibold">
                + Tambah Pertanyaan
            </a>
        </div>

        @if($quiz->questions->isEmpty())
            <p class="text-white/70">Belum ada pertanyaan.</p>
        @else
            <div class="space-y-4">
                @foreach($quiz->questions as $q)
                    <div class="bg-white/5 p-4 rounded-lg">
                        <div class="flex justify-between">
                            <span class="font-bold text-lg">{{ $q->order_index }}. {{ $q->question }}</span>
                            <div class="flex gap-2">
                                <a href="{{ route('quiz.question.edit', [$quiz->id, $q->id]) }}"
                                   class="px-3 py-1 bg-yellow-600 rounded-md">Edit</a>
                                <form method="POST"
                                      action="{{ route('quiz.question.delete', [$quiz->id, $q->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 rounded-md"
                                            onclick="return confirm('Yakin?')">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-2 pl-4 space-y-1">
                            @foreach($q->choices as $c)
                                <p class="text-white/80">
                                    <span class="font-bold">{{ $c->label }}.</span>
                                    {{ $c->text }}
                                    @if($c->is_correct)
                                        <span class="text-green-400 ml-2 font-bold">(benar)</span>
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
