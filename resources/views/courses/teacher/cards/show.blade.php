@extends('layouts.dashboard')
@section('title', 'Detail Card')

@section('content')
@php
    $card = $card ?? null;
    $content = $card?->content;
    $lesson = $content?->lesson;
    $course = $lesson?->course;
    $blocks = ($card?->blocks ?? collect())->sortBy('order_index')->values();
    $typeColors = [
        'text'  => 'bg-emerald-500/20 text-emerald-100 border-emerald-400/40',
        'image' => 'bg-sky-500/20 text-sky-100 border-sky-400/40',
        'gif'   => 'bg-indigo-500/20 text-indigo-100 border-indigo-400/40',
        'video' => 'bg-amber-500/20 text-amber-50 border-amber-400/40',
        'quiz'  => 'bg-rose-500/20 text-rose-50 border-rose-400/40',
        'code'  => 'bg-slate-500/20 text-slate-100 border-slate-400/40',
    ];
@endphp

<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-8">
        <x-dashboard-header
            title="Detail Card"
            subtitle="{{ $course->title ?? 'Course' }} - Lesson {{ $lesson->order_index ?? '-' }} - Content {{ $content->order_index ?? '-' }}"
        />

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="{{ route('teacher.courses.lessons.show', [$course, $lesson]) }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Lesson
            </a>
            <a href="{{ route('teacher.courses.show', $course) }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-book"></i>
                Detail Course
            </a>
            <span class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-4 py-2.5 text-sm font-semibold shadow-md">
                <i class="fa-solid fa-layer-group"></i>
                Card #{{ $card->order_index }}
            </span>
        </div>

        <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="space-y-1">
                    <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Informasi Card</p>
                    <h1 class="m-0 text-xl font-black">Lesson: {{ $lesson->title }}</h1>
                    <p class="m-0 text-sm opacity-75">Content: {{ $content->title }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs uppercase tracking-wide opacity-75">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1">
                        Urutan #{{ $card->order_index }}
                    </span>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1">
                        <i class="fa-solid fa-cube"></i>
                        <span data-stat-blocks>{{ $blocks->count() }}</span> Blocks
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl border border-dashed border-white/20 bg-white/5 p-5 shadow-2xl space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Block Baru</p>
                        <h2 class="m-0 text-lg font-semibold">Tambah block ke Card #{{ $card->order_index }}</h2>
                    </div>
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                        Card #{{ $card->id }}
                    </span>
                </div>

                <form action="{{ route('blocks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3" data-block-create>
                    @csrf
                    <input type="hidden" name="card_id" value="{{ $card->id }}">
                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="content_id" value="{{ $content->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wide opacity-70">Urutan Block</label>
                            <input type="number" name="order_index" min="1" value="{{ ($blocks->max('order_index') ?? 0) + 1 }}"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        </div>
                        <div class="md:col-span-3 space-y-2">
                            <label class="text-xs uppercase tracking-wide opacity-70">Tipe Block</label>
                            <select name="type" data-block-type
                                    class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                <option value="text">Text</option>
                                <option value="image">Image</option>
                                <option value="gif">GIF</option>
                                <option value="video">Video</option>
                                <option value="quiz">Quiz</option>
                                <option value="code">Code</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2" data-fields-for="text">
                        <label class="text-xs uppercase tracking-wide opacity-70">Konten Teks</label>
                        <textarea name="data[content]" rows="3" data-require-for="text"
                                  class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Tuliskan konten..."></textarea>
                    </div>

                    <div class="space-y-2 hidden" data-fields-for="quiz">
                        <label class="text-xs uppercase tracking-wide opacity-70">Pertanyaan</label>
                        <input type="text" name="data[question]" data-require-for="quiz"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Tulis pertanyaan">
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            @foreach (['A','B','C','D'] as $opt)
                                <div class="space-y-1">
                                    <label class="text-xs uppercase tracking-wide opacity-70">Pilihan {{ $opt }}</label>
                                    <input type="text" name="data[choices][{{ $opt }}]" data-require-for="quiz"
                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                                </div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wide opacity-70">Jawaban</label>
                                <select name="data[answer]" data-require-for="quiz"
                                        class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                    <option value="" disabled selected>Pilih jawaban</option>
                                    @foreach (['A','B','C','D'] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wide opacity-70">Penjelasan</label>
                                <textarea name="data[explanation]" rows="2" data-require-for="quiz"
                                          class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 hidden" data-fields-for="image">
                        <label class="text-xs uppercase tracking-wide opacity-70">File Gambar</label>
                        <input type="file" name="data[file]" accept=".jpg,.jpeg,.png" data-require-for="image"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        <input type="text" name="data[alt]"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                    </div>

                    <div class="space-y-2 hidden" data-fields-for="gif">
                        <label class="text-xs uppercase tracking-wide opacity-70">File GIF</label>
                        <input type="file" name="data[file]" accept=".gif" data-require-for="gif"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        <input type="text" name="data[alt]"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                    </div>

                    <div class="space-y-2 hidden" data-fields-for="video">
                        <label class="text-xs uppercase tracking-wide opacity-70">File Video (mp4)</label>
                        <input type="file" name="data[file]" accept=".mp4" data-require-for="video"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        <input type="text" name="data[alt]"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                    </div>

                    <div class="space-y-2 hidden" data-fields-for="code">
                        <label class="text-xs uppercase tracking-wide opacity-70">Bahasa</label>
                        <input type="text" name="data[language]" data-require-for="code"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Contoh: php, js">
                        <label class="text-xs uppercase tracking-wide opacity-70">Kode</label>
                        <textarea name="data[code]" rows="3" data-require-for="code"
                                  class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Tempelkan kode di sini"></textarea>
                    </div>

                    <div class="flex flex-wrap gap-2 justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-circle-plus"></i>
                            Tambah Block
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 shadow-2xl space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Daftar Block</p>
                        <h2 class="m-0 text-lg font-semibold">Block pada card ini</h2>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                        <i class="fa-solid fa-cube"></i>
                        <span data-stat-blocks>{{ $blocks->count() }}</span> Blocks
                    </span>
                </div>
                <div class="space-y-3" data-block-list>
                    <p class="m-0 text-sm opacity-75" data-empty-block>Memuat block...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const $ = window.jQuery;
        if (!$) return;

        const token = '{{ csrf_token() }}';
        const baseBlockUrl = '{{ url('/blocks') }}';
        const blocksEndpoint = '{{ route('card.get-blocks', $card) }}';
        const storeUrl = '{{ route('blocks.store') }}';
        const typeColors = @json($typeColors);

        let blocks = @json($blocks);
        const blockList = $('[data-block-list]');
        const statBlocks = document.querySelectorAll('[data-stat-blocks]');

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
            alert(msg);
        };

        const typeValue = (type) => {
            if (typeof type === 'string') return type;
            if (type && typeof type === 'object' && 'value' in type) return type.value;
            return type || 'text';
        };

        const escapeHtml = (value = '') => $('<div>').text(value ?? '').html();

        const renderPreview = (block) => {
            const type = typeValue(block.type);
            const data = block.data || {};
            switch (type) {
                case 'text':
                    return `<p class="m-0 opacity-85">${escapeHtml(data.content || '')}</p>`;
                case 'image':
                case 'gif':
                case 'video':
                    return [
                        `<p class="m-0 opacity-85">File: ${escapeHtml(data.filename || 'Tidak ada file')}</p>`,
                        `<p class="m-0 text-xs opacity-70">Alt: ${escapeHtml(data.alt || '-')}</p>`
                    ].join('');
                case 'quiz': {
                    const choices = data.choices || {};
                    const items = Object.keys(choices).map((key) => {
                        const isAnswer = (data.answer || '') === key;
                        const answerClass = isAnswer ? 'font-semibold text-emerald-200' : '';
                        return `<li class="${answerClass}"><span class="font-bold">${escapeHtml(key)}.</span> ${escapeHtml(choices[key] || '')}</li>`;
                    }).join('');
                    return [
                        `<p class="m-0 font-semibold">${escapeHtml(data.question || 'Pertanyaan')}</p>`,
                        `<ul class="m-0 list-disc list-inside text-xs opacity-80 space-y-1">${items}</ul>`,
                        `<p class="m-0 text-xs opacity-70">Jawaban: ${escapeHtml(data.answer || '-')}</p>`
                    ].join('');
                }
                case 'code':
                    return [
                        `<p class="m-0 text-xs uppercase tracking-wide opacity-70">${escapeHtml(data.language || 'language')}</p>`,
                        `<pre class="rounded-lg bg-black/50 p-3 text-xs overflow-auto"><code>${escapeHtml(data.code || '')}</code></pre>`
                    ].join('');
                default:
                    return `<p class="m-0 text-xs opacity-70">Data: ${escapeHtml(JSON.stringify(data))}</p>`;
            }
        };

        const syncTypeFields = (form) => {
            const typeInput = form.find('[data-block-type]');
            const type = typeValue(typeInput.val() || form.data('blockType') || 'text');
            form.find('[data-fields-for]').each(function () {
                const allowed = (this.dataset.fieldsFor || '').split(',').includes(type);
                this.classList.toggle('hidden', !allowed);
            });
            form.find('[data-require-for]').each(function () {
                const required = (this.dataset.requireFor || '').split(',').includes(type);
                this.required = required;
            });
        };

        const buildBlockNode = (block) => {
            const type = typeValue(block.type);
            const data = block.data || {};
            const badgeColor = typeColors[type] || 'bg-slate-500/20 text-slate-100 border-slate-400/40';
            const node = $(
                `<div class="rounded-xl border border-white/12 bg-white/5 p-4 shadow-md space-y-3" data-block-id="${block.id}" data-order="${block.order_index}">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide ${badgeColor}">
                                ${escapeHtml(type)}
                            </span>
                            <span class="text-xs opacity-70">Urutan #${block.order_index}</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" data-toggle-block-edit class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </button>
                            <button type="button" data-delete-block data-block-id="${block.id}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-xs font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2 text-sm leading-relaxed" data-block-display>
                        ${renderPreview(block)}
                    </div>

                    <form class="space-y-3 rounded-lg border border-white/10 bg-white/5 p-3 hidden" data-block-edit data-block-id="${block.id}" data-block-type="${type}">
                        <input type="hidden" name="_token" value="${token}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="card_id" value="{{ $card->id }}">
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="content_id" value="{{ $content->id }}">
                        <input type="hidden" name="type" value="${type}" data-block-type>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="space-y-2">
                                <label class="text-xs uppercase tracking-wide opacity-70">Urutan Block</label>
                                <input type="number" name="order_index" min="1" value="${block.order_index}"
                                       class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs uppercase tracking-wide opacity-70">Tipe</label>
                                <input type="text" value="${type}" disabled
                                       class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white/70">
                            </div>
                        </div>

                        <div class="space-y-2" data-fields-for="text">
                            <label class="text-xs uppercase tracking-wide opacity-70">Konten</label>
                            <textarea name="data[content]" rows="3" data-require-for="text" data-fill="text-content"
                                      class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none"></textarea>
                        </div>

                        <div class="space-y-2" data-fields-for="quiz">
                            <label class="text-xs uppercase tracking-wide opacity-70">Pertanyaan</label>
                            <input type="text" name="data[question]" data-require-for="quiz" data-fill="quiz-question"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                ${['A','B','C','D'].map((opt) => `
                                    <div class="space-y-1">
                                        <label class="text-xs uppercase tracking-wide opacity-70">Pilihan ${opt}</label>
                                        <input type="text" name="data[choices][${opt}]" data-require-for="quiz" data-fill="quiz-choice-${opt}"
                                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                                    </div>
                                `).join('')}
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="space-y-1">
                                    <label class="text-xs uppercase tracking-wide opacity-70">Jawaban</label>
                                    <select name="data[answer]" data-require-for="quiz" data-fill="quiz-answer"
                                            class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                        <option value="" disabled>Pilih jawaban</option>
                                        ${['A','B','C','D'].map((opt) => `<option value="${opt}">${opt}</option>`).join('')}
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs uppercase tracking-wide opacity-70">Penjelasan</label>
                                    <textarea name="data[explanation]" rows="2" data-require-for="quiz" data-fill="quiz-explanation"
                                              class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2" data-fields-for="image">
                            <label class="text-xs uppercase tracking-wide opacity-70">File Gambar</label>
                            <input type="file" name="data[file]" accept=".jpg,.jpeg,.png" data-require-for="image"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                            <input type="text" name="data[alt]" data-fill="img-alt"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text">
                        </div>

                        <div class="space-y-2" data-fields-for="gif">
                            <label class="text-xs uppercase tracking-wide opacity-70">File GIF</label>
                            <input type="file" name="data[file]" accept=".gif" data-require-for="gif"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                            <input type="text" name="data[alt]" data-fill="gif-alt"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text">
                        </div>

                        <div class="space-y-2" data-fields-for="video">
                            <label class="text-xs uppercase tracking-wide opacity-70">File Video (mp4)</label>
                            <input type="file" name="data[file]" accept=".mp4" data-require-for="video"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                            <input type="text" name="data[alt]" data-fill="video-alt"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text">
                        </div>

                        <div class="space-y-2" data-fields-for="code">
                            <label class="text-xs uppercase tracking-wide opacity-70">Bahasa</label>
                            <input type="text" name="data[language]" data-require-for="code" data-fill="code-language"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                            <label class="text-xs uppercase tracking-wide opacity-70">Kode</label>
                            <textarea name="data[code]" rows="3" data-require-for="code" data-fill="code-body"
                                      class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none"></textarea>
                        </div>

                        <div class="flex flex-wrap gap-2 justify-end">
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                                Simpan Block
                            </button>
                            <button type="button" data-toggle-block-edit
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                Tutup
                            </button>
                        </div>
                    </form>
                </div>`
            );

            node.find('[data-fill="text-content"]').val(data.content || '');
            node.find('[data-fill="quiz-question"]').val(data.question || '');
            ['A','B','C','D'].forEach((opt) => {
                node.find(`[data-fill="quiz-choice-${opt}"]`).val(data.choices?.[opt] || '');
            });
            node.find('[data-fill="quiz-answer"]').val(data.answer || '');
            node.find('[data-fill="quiz-explanation"]').val(data.explanation || '');
            node.find('[data-fill="img-alt"]').val(data.alt || '');
            node.find('[data-fill="gif-alt"]').val(data.alt || '');
            node.find('[data-fill="video-alt"]').val(data.alt || '');
            node.find('[data-fill="code-language"]').val(data.language || '');
            node.find('[data-fill="code-body"]').val(data.code || '');

            syncTypeFields(node.find('[data-block-edit]'));
            return node;
        };

        const updateStatBlocks = (total) => {
            statBlocks.forEach((el) => el.textContent = total);
        };

        const renderBlocks = () => {
            blockList.empty();
            if (!blocks.length) {
                blockList.append('<p class="m-0 text-sm opacity-75" data-empty-block>Belum ada block di card ini.</p>');
                updateStatBlocks(0);
                return;
            }
            const sorted = [...blocks].sort((a, b) => (a.order_index || 0) - (b.order_index || 0));
            updateStatBlocks(sorted.length);
            sorted.forEach((block) => blockList.append(buildBlockNode(block)));
        };

        const fetchBlocks = (message = null) => {
            $.ajax({
                url: blocksEndpoint,
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                success: (res) => {
                    blocks = Array.isArray(res) ? res : [];
                    renderBlocks();
                    const nextOrder = (blocks.reduce((max, b) => Math.max(max, parseInt(b.order_index, 10) || 0), 0) + 1);
                    $('[data-block-create] input[name="order_index"]').val(nextOrder);
                    if (message) toast(message);
                },
                error: () => {
                    toast('Gagal memuat block', false);
                }
            });
        };

        renderBlocks();

        $('[data-block-create] [data-block-type]').on('change', function () {
            syncTypeFields($(this).closest('form'));
        }).trigger('change');

        $('[data-block-create]').on('submit', function (e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);
            $.ajax({
                url: storeUrl,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: () => {
                    form.trigger('reset');
                    syncTypeFields(form);
                    fetchBlocks('Block ditambahkan');
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal menambah block';
                    toast(msg, false);
                }
            });
        });

        $(document).on('click', '[data-toggle-block-edit]', function () {
            const container = $(this).closest('[data-block-id]');
            const form = container.find('[data-block-edit]');
            form.toggleClass('hidden');
            if (!form.hasClass('hidden')) {
                form.find('input[name="order_index"]').trigger('focus');
            }
        });

        $(document).on('submit', '[data-block-edit]', function (e) {
            e.preventDefault();
            const form = $(this);
            const blockId = form.data('block-id');
            const formData = new FormData(this);
            formData.set('_method', 'PUT');
            $.ajax({
                url: `${baseBlockUrl}/${blockId}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: () => {
                    fetchBlocks('Block diperbarui');
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal memperbarui block';
                    toast(msg, false);
                }
            });
        });

        $(document).on('click', '[data-delete-block]', function () {
            const blockId = $(this).data('block-id');
            const proceed = () => {
                $.ajax({
                    url: `${baseBlockUrl}/${blockId}`,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                    success: () => {
                        fetchBlocks('Block dihapus');
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus block';
                        toast(msg, false);
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus block?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    reverseButtons: true,
                }).then((res) => res.isConfirmed && proceed());
            } else if (confirm('Hapus block ini?')) {
                proceed();
            }
        });
    });
</script>
@endsection
