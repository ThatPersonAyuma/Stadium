@extends('layouts.dashboard')
@section('title', 'Detail Course')

@section('content')
@php
    $lessons = $course->lessons ?? collect();
    $stats = $stats ?? ['lessons' => 0, 'contents' => 0, 'cards' => 0, 'blocks' => 0];
@endphp
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-10">
        <x-dashboard-header title="Detail Course" subtitle="{{ $course->title }}" />
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('teacher.courses.index') }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
            <a href="{{ route('teacher.courses.edit', $course) }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-4 py-2.5 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                <i class="fa-solid fa-pen-to-square"></i>
                Edit Course
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <p class="m-0 text-sm opacity-85 whitespace-pre-line">{{ $course->description ?: 'Belum ada deskripsi course.' }}</p>
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
                        <input type="number" name="order_index" min="1" placeholder="Urutan" value="{{ (\App\Models\Lesson::where('course_id', $course->id)->max('order_index') ?? 0) + 1 }}" max="{{ (\App\Models\Lesson::where('course_id', $course->id)->max('order_index') ?? 0) + 1 }}"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                            <i class="fa-solid fa-circle-plus"></i>
                            Tambah
                        </button>
                    </div>
                </form>
                <p id="lesson-feedback" class="m-0 text-sm text-emerald-200 opacity-0 transition-opacity"></p>
            </div>
        </div>

        <div class="space-y-4" id="lesson-list">
            @forelse ($lessons as $lesson)
                @php
                    $contentCount = $lesson->contents->count();
                    $cardCount = $lesson->contents->flatMap->cards->count();
                    $blockCount = $lesson->contents->flatMap->cards->flatMap->blocks->count();
                @endphp
                <div class="rounded-2xl border border-white/12 bg-white/5 p-5 shadow-lg space-y-3" data-lesson-id="{{ $lesson->id }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="space-y-1">
                            <p class="m-0 text-xs uppercase tracking-[0.2em] text-white/70">Lesson {{ $lesson->order_index }}</p>
                            <h3 class="m-0 text-xl font-black leading-tight">{{ $lesson->title }}</h3>
                            <p class="m-0 text-sm opacity-80">{{ $lesson->description ?: 'Belum ada deskripsi lesson.' }}</p>
                            <div class="flex flex-wrap gap-3 text-xs uppercase tracking-wide opacity-70">
                                <span>Contents: {{ $contentCount }}</span>
                                <span>Cards: {{ $cardCount }}</span>
                                <span>Blocks: {{ $blockCount }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}"
                               class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-layer-group"></i>
                                Kelola Blocks
                            </a>
                            <button type="button" data-delete-lesson data-delete-url="{{ url('teacher/courses/'.$course->id.'/lessons/'.$lesson->id) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-sm font-semibold text-rose-50 shadow-md transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="m-0 text-sm opacity-80">Belum ada lesson. Tambahkan lesson pertama Anda.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const $ = window.jQuery;
        if (!$) return;
        const form = $('#lesson-form');
        const list = $('#lesson-list');
        const feedback = $('#lesson-feedback');
        const token = '{{ csrf_token() }}';
        const storeUrl = "{{ route('teacher.courses.lessons.store', $course) }}";
        const lessonBaseUrl = "{{ url('teacher/courses/'.$course->id.'/lessons') }}";
        const lessonManageBase = "{{ url('teacher/courses/'.$course->id.'/lessons') }}";

        const toast = (msg, ok = true) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    timer: 2200,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    icon: ok ? 'success' : 'error',
                    title: msg,
                });
                return;
            }
            feedback.text(msg).css('opacity', 1)
                .toggleClass('text-emerald-200', ok)
                .toggleClass('text-rose-200', !ok);
            setTimeout(() => feedback.css('opacity', 0), 2500);
        };

        const renderLesson = (lesson) => {
            return $(
                `<div class="rounded-2xl border border-white/12 bg-white/5 p-5 shadow-lg space-y-3" data-lesson-id="${lesson.id}" data-order="${lesson.order_index}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="space-y-1">
                            <p class="m-0 text-xs uppercase tracking-[0.2em] text-white/70">Lesson ${lesson.order_index}</p>
                            <h3 class="m-0 text-xl font-black leading-tight">${lesson.title}</h3>
                            <p class="m-0 text-sm opacity-80">${lesson.description || 'Belum ada deskripsi lesson.'}</p>
                            <div class="flex flex-wrap gap-3 text-xs uppercase tracking-wide opacity-70">
                                <span>Contents: 0</span>
                                <span>Cards: 0</span>
                                <span>Blocks: 0</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="${lessonManageBase}/${lesson.id}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-layer-group"></i> Kelola Blocks
                            </a>
                            <button type="button" data-delete-lesson data-delete-url="${lessonBaseUrl}/${lesson.id}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-sm font-semibold text-rose-50 shadow-md transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>`
            );
        };

        const insertLessonSorted = (node) => {
            const newOrder = parseInt(node.data('order'), 10);
            let placed = false;
            list.children('[data-lesson-id]').each(function() {
                const existingOrder = parseInt($(this).data('order'), 10);
                if (newOrder < existingOrder) {
                    $(this).before(node);
                    placed = true;
                    return false;
                }
            });
            if (!placed) list.append(node);
        };

        form.on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const desiredOrder = parseInt(formData.get('order_index') || '0', 10);
            if (desiredOrder) {
                const duplicate = list.children('[data-order]').filter(function() {
                    return parseInt($(this).data('order'), 10) === desiredOrder;
                }).length > 0;
                if (duplicate) {
                    toast('Urutan lesson sudah digunakan.', false);
                    return;
                }
            }
            $.ajax({
                url: storeUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: (data) => {
                    const card = renderLesson(data.lesson);
                    insertLessonSorted(card);
                    this.reset();
                    toast(data.message || 'Lesson ditambahkan');
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal menambah lesson';
                    toast(msg, false);
                }
            });
        });

        list.on('click', '[data-delete-lesson]', function() {
            const btn = $(this);
            const url = btn.data('delete-url');
            const wrapper = btn.closest('[data-lesson-id]');
            const proceed = () => {
                $.ajax({
                    url,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                    success: (data) => {
                        wrapper.remove();
                        toast(data.message || 'Lesson dihapus');
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus lesson';
                        toast(msg, false);
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus lesson?',
                    text: 'Aksi ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    reverseButtons: true,
                }).then((res) => res.isConfirmed && proceed());
            } else if (confirm('Yakin ingin menghapus lesson ini?')) {
                proceed();
            }
        });
    });
</script>
@endsection
