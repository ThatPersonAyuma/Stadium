@extends('layouts.dashboard')
@section('title', 'Tambah Course')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-6">
        <x-dashboard-header title="Tambah Course" subtitle="Buat course baru untuk siswa" />

        <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl">
            <form action="{{ route('courses.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="block text-sm font-semibold" for="title">Judul</label>
                    <input id="title" name="title" type="text" required
                           value="{{ old('title') }}"
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold" for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                <div class="space-y-2" data-select-chevron>
                    <label class="block text-sm font-semibold" for="status">Status</label>
                    <div class="group relative">
                        <select id="status" name="status"
                                class="peer w-full appearance-none rounded-xl border border-white/20 bg-white/10 px-4 py-3 pr-12 text-white placeholder-white/60 focus:border-white/60 focus:outline-none transition">
                            @php
                                $statuses = ['draft','pending','approved','revision','rejected','hidden','archived'];
                                $current = old('status', 'draft');
                            @endphp
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($current === $status) class="bg-slate-800 text-white">
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-white/70 transition-transform duration-200 ease-out">
                            <i class="fa-solid fa-chevron-down"></i>
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                        <i class="fa-solid fa-circle-check"></i>
                        Simpan Course
                    </button>
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                        <i class="fa-solid fa-arrow-left"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.querySelector('[data-select-chevron]');
        if (!wrapper) return;
        const select = wrapper.querySelector('select');
        const icon = wrapper.querySelector('span i');
        if (!select || !icon) return;

        const setState = (open) => {
            icon.classList.toggle('rotate-180', open);
        };

        select.addEventListener('mousedown', () => setState(true));
        select.addEventListener('click', () => setState(true));
        select.addEventListener('focus', () => setState(true));
        select.addEventListener('blur', () => setState(false));
        select.addEventListener('change', () => setState(false));
    });
</script>
