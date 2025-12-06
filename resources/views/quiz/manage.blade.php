@extends('layouts.dashboard')
@section('title', "Manage Quiz: $quiz->title")

@php
    // Status yang boleh dipilih user
    $selectable = [
        App\Enums\CourseStatus::DRAFT,
        App\Enums\CourseStatus::PENDING,
    ];
@endphp

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">

    {{-- Background --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-8">

        {{-- Header --}}
        <x-dashboard-header 
            :title="'Kelola Quiz – ' . $quiz->title"
            subtitle="Edit detail quiz dan atur pertanyaan"
        />

        {{-- ======================= --}}
        {{-- FORM EDIT QUIZ --}}
        {{-- ======================= --}}
        <form action="{{ route('quiz.update', $quiz->id) }}" method="POST"
              class="bg-white/10 p-6 rounded-2xl shadow-xl backdrop-blur">
            @csrf
            @method('PUT')

            <h2 class="text-xl font-bold mb-4">Edit Informasi Quiz</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Title --}}
                <div>
                    <label class="font-semibold">Judul Quiz<br>{{}}</label>
                    <input type="text" name="title"
                        class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                        value="{{ $quiz->title }}" required>
                </div>

            </div>

            {{-- Description --}}
            <div class="mt-4">
                <label class="font-semibold">Deskripsi</label>
                <textarea name="description"
                    class="w-full mt-2 p-3 bg-white/5 rounded-lg"
                    rows="3">{{ $quiz->description }}</textarea>
            </div>

            {{-- Status --}}
            <div class="mt-4">
                <label class="font-semibold">Status</label>
                <select name="status"
                    class="w-full mt-2 p-3 bg-white/5 rounded-lg">
                    @foreach(\App\Enums\CourseStatus::cases() as $status)
                        <option value="{{ $status->value }}"
                            {{ $quiz->status === $status->value ? 'selected' : '' }}
                            {{ in_array($status, $selectable) ? '' : 'disabled' }}>
                            {{ strtoupper($status->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button class="mt-6 bg-blue-600 hover:bg-blue-500 px-6 py-3 rounded-lg font-bold w-full">
                Perbarui Quiz
            </button>
        </form>

        {{-- ======================= --}}
        {{-- TOMBOL TAMBAH PERTANYAAN --}}
        {{-- ======================= --}}
        <a href="{{ route('quiz.question.create', $quiz->id) }}"
           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5">
            <i class="fa-solid fa-circle-plus"></i>
            Tambah Pertanyaan
        </a>

        {{-- ======================= --}}
        {{-- LIST PERTANYAAN --}}
        {{-- ======================= --}}
        <div class="grid grid-cols-1 gap-6">
            @forelse($quiz->questions as $q)
                <div class="rounded-2xl border border-white/15 bg-white/10 p-5 shadow-2xl">

                    {{-- Top row --}}
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="m-0 text-lg font-black leading-tight">
                            {{ $q->order_index }}. {{ $q->question }}
                        </h3>

                        <div class="flex gap-2">

                            {{-- Edit --}}
                            <a href="{{ route('quiz.question.edit', [$quiz->id, $q->id]) }}"
                               class="inline-flex items-center gap-2 rounded-lg border border-yellow-300/40 bg-yellow-500/20 px-3 py-1.5 text-sm font-semibold text-yellow-100 shadow-md transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('quiz.question.delete', [$quiz->id, $q->id]) }}" method="POST" data-question-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="button" data-delete-question
                                    class="inline-flex items-center gap-2 rounded-lg border border-rose-300/40 bg-rose-500/20 px-3 py-1.5 text-sm font-semibold text-rose-100 shadow-md transition hover:-translate-y-0.5">
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </div>

                    {{-- Choices --}}
                    <div class="mt-4 space-y-1 pl-2">
                        @foreach($q->choices as $c)
                            <p class="text-sm opacity-85">
                                <span class="font-black">{{ $c->label }}.</span>
                                {{ $c->text }}

                                @if($c->is_correct)
                                    <span class="ml-2 rounded-full bg-green-500/20 px-2 py-0.5 text-xs font-bold text-green-300">
                                        Benar
                                    </span>
                                @endif
                            </p>
                        @endforeach
                    </div>
                </div>

            @empty
                <div class="col-span-full rounded-2xl border border-white/15 bg-white/5 p-6 text-sm opacity-80">
                    Belum ada pertanyaan. Tambahkan pertanyaan pertama Anda.
                </div>
            @endforelse
        </div>

    </div>
</div>

{{-- JavaScript Delete Confirmation --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    const deleteForms = document.querySelectorAll('[data-question-delete-form]');
    if (!deleteForms.length) return;

    deleteForms.forEach(initDeleteHandler);
});


function initDeleteHandler(form) {
    const btn = form.querySelector('[data-delete-question]');
    if (!btn) return;

    btn.addEventListener('click', (e) => {
        e.preventDefault();
        confirmDelete(() => executeDelete(form));
    });
}

function confirmDelete(onConfirm) {
    Swal.fire({
        title: 'Hapus pertanyaan?',
        text: 'Aksi ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then(result => {
        if (result.isConfirmed) onConfirm();
    });
}

function executeDelete(form) {
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: new URLSearchParams({ '_method': 'DELETE' })
    })
    .then(handleResponse)
    .then(data => {
        showSuccess(data.message);
        animateRemove(form.closest('.rounded-2xl'));
    })
    .catch(showError);
}

function handleResponse(res) {
    if (!res.ok) throw new Error("Server error");
    return res.json();
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.reload();
    });
}

function showError(err) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Terjadi kesalahan saat menghapus.'
    });
    console.error(err);
}
function animateRemove(element) {
    if (!element) return;

    element.style.transition = "opacity 0.3s, transform 0.3s";
    element.style.opacity = "0";
    element.style.transform = "scale(0.95)";

    setTimeout(() => element.remove(), 300);
}

</script>
@endsection
