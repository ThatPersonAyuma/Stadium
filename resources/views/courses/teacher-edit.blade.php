@extends('layouts.dashboard')
@section('title', 'Edit Course')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-4xl space-y-6">
        <x-dashboard-header title="Edit Course" subtitle="{{ $course->title }}" />

        <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl">
            <form action="{{ route('courses.update', $course) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="space-y-2">
                    <label class="block text-sm font-semibold" for="title">Judul</label>
                    <input id="title" name="title" type="text" required
                           value="{{ old('title', $course->title) }}"
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold" for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold" for="status">Status</label>
                    <select id="status" name="status"
                            class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                        @php
                            $statuses = ['draft','pending','approved','revision','rejected','hidden','archived'];
                            $current = old('status', $course->status ?? 'draft');
                        @endphp
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($current === $status) class="bg-slate-800 text-white">
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('teacher.courses.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
