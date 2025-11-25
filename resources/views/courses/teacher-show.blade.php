@extends('layouts.dashboard')
@section('title', 'Detail Course')

@section('content')
@php
    $lessons = $course->lessons ?? collect();
    $stats = $stats ?? ['lessons' => 0, 'contents' => 0, 'cards' => 0, 'blocks' => 0];
@endphp
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-8 pb-12 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <x-dashboard-header title="Detail Course" subtitle="{{ $course->title }}" />
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('teacher.courses.edit', $course) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                    Edit Course
                </a>
                <a href="{{ route('teacher.courses.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 bg-white/10 px-3 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Status</p>
                <p class="m-0 text-2xl font-black capitalize">{{ $course->status ?? 'draft' }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Lessons</p>
                <p class="m-0 text-2xl font-black">{{ $stats['lessons'] }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Contents</p>
                <p class="m-0 text-2xl font-black">{{ $stats['contents'] }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Blocks</p>
                <p class="m-0 text-2xl font-black">{{ $stats['blocks'] }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl space-y-6">
            <div class="space-y-2">
                <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Deskripsi</p>
                <p class="m-0 text-sm opacity-85 whitespace-pre-line">{{ $course->description }}</p>
            </div>

            <div class="space-y-2">
                <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Tambah Lesson</p>
                <form id="lesson-form" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <input type="text" name="title" placeholder="Judul lesson" required
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                    <input type="text" name="description" placeholder="Deskripsi (opsional)"
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                    <div class="flex gap-2">
                        <input type="number" name="order_index" min="1" placeholder="Urutan"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                            Tambah
                        </button>
                    </div>
                </form>
                <p id="lesson-feedback" class="m-0 text-sm text-emerald-200 opacity-0 transition-opacity"></p>
            </div>

            <div class="space-y-4" id="lesson-list">
                @forelse ($lessons as $lesson)
                    <div class="rounded-2xl border border-white/15 bg-white/5 p-5 shadow-lg space-y-3" data-lesson-id="{{ $lesson->id }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Lesson {{ $lesson->order_index }}</p>
                                <h3 class="m-0 text-xl font-black leading-tight">{{ $lesson->title }}</h3>
                                <p class="m-0 text-sm opacity-80">{{ $lesson->description }}</p>
                            </div>
                            <button type="button" data-delete-lesson data-delete-url="{{ url('teacher/courses/'.$course->id.'/lessons/'.$lesson->id) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                Hapus
                            </button>
                        </div>

                        <div class="space-y-2">
                            <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Contents</p>
                            @forelse ($lesson->contents as $content)
                                <div class="rounded-xl border border-white/10 bg-white/5 p-4 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="m-0 text-xs uppercase tracking-widest opacity-60">Content {{ $content->order_index }}</p>
                                            <p class="m-0 font-semibold">{{ $content->title }}</p>
                                        </div>
                                        <span class="text-xs opacity-70">{{ $content->cards->count() }} cards</span>
                                    </div>
                                    @forelse ($content->cards as $card)
                                        <div class="rounded-lg border border-white/10 bg-white/5 p-3 space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs uppercase tracking-wide opacity-60">Card {{ $card->order_index }}</span>
                                                <span class="text-xs opacity-70">{{ $card->blocks->count() }} blocks</span>
                                            </div>
                                            <div class="grid grid-cols-1 gap-2">
                                                @forelse ($card->blocks as $block)
                                                    @php
                                                        $payload = is_array($block->data) ? json_encode($block->data) : (string) $block->data;
                                                    @endphp
                                                    <div class="rounded-md border border-white/10 bg-white/5 px-3 py-2 text-xs">
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-semibold uppercase tracking-wide">{{ $block->type }}</span>
                                                            <span class="opacity-70">#{{ $block->order_index }}</span>
                                                        </div>
                                                        <p class="m-0 opacity-80">{{ \Illuminate\Support\Str::limit($payload, 120) }}</p>
                                                    </div>
                                                @empty
                                                    <p class="m-0 text-xs opacity-70">Belum ada block.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <p class="m-0 text-xs opacity-70">Belum ada card.</p>
                                    @endforelse
                                </div>
                            @empty
                                <p class="m-0 text-sm opacity-70">Belum ada content.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="m-0 text-sm opacity-70">Belum ada lesson. Tambahkan lesson pertama Anda.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('lesson-form');
        const list = document.getElementById('lesson-list');
        const feedback = document.getElementById('lesson-feedback');
        const token = '{{ csrf_token() }}';
        const storeUrl = "{{ route('teacher.courses.lessons.store', $course) }}";
        const lessonBaseUrl = "{{ url('teacher/courses/'.$course->id.'/lessons') }}";

        const showFeedback = (msg, ok = true) => {
            feedback.textContent = msg;
            feedback.style.opacity = '1';
            feedback.classList.toggle('text-emerald-200', ok);
            feedback.classList.toggle('text-rose-200', !ok);
            setTimeout(() => feedback.style.opacity = '0', 2500);
        };

        const renderLesson = (lesson) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'rounded-2xl border border-white/15 bg-white/5 p-5 shadow-lg space-y-3';
            wrapper.dataset.lessonId = lesson.id;
            wrapper.innerHTML = `
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Lesson ${lesson.order_index}</p>
                        <h3 class="m-0 text-xl font-black leading-tight">${lesson.title}</h3>
                        <p class="m-0 text-sm opacity-80">${lesson.description || ''}</p>
                    </div>
                    <button type="button" data-delete-lesson data-delete-url="${lessonBaseUrl}/${lesson.id}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                        Hapus
                    </button>
                </div>
                <p class="m-0 text-sm opacity-70">Belum ada content.</p>
            `;
            return wrapper;
        };

        form?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            try {
                const res = await fetch(storeUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                });
                const data = await res.json();
                if (!res.ok) {
                    const message = data?.message || 'Gagal menambah lesson';
                    showFeedback(message, false);
                    return;
                }
                const card = renderLesson(data.lesson);
                list.prepend(card);
                form.reset();
                showFeedback(data.message || 'Lesson ditambahkan');
            } catch (err) {
                showFeedback('Terjadi kesalahan saat menambah lesson', false);
            }
        });

        list?.addEventListener('click', async (e) => {
            const btn = e.target.closest('[data-delete-lesson]');
            if (!btn) return;
            const url = btn.dataset.deleteUrl;
            const wrapper = btn.closest('[data-lesson-id]');
            try {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                });
                const data = await res.json();
                if (!res.ok) {
                    showFeedback(data?.message || 'Gagal menghapus lesson', false);
                    return;
                }
                wrapper?.remove();
                showFeedback(data.message || 'Lesson dihapus');
            } catch (err) {
                showFeedback('Terjadi kesalahan saat menghapus lesson', false);
            }
        });
    });
</script>
@endsection
