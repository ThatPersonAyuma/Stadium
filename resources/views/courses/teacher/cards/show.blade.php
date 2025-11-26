@extends('layouts.dashboard')
@section('title', 'Detail Card')

@section('content')
@php
    $card = $card ?? null;
    $content = $card?->content;
    $lesson = $content?->lesson;
    $course = $lesson?->course;
    $blocks = $card?->blocks ?? collect();
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

    <div class="relative z-10 mx-auto max-w-6xl space-y-10">
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
            <div class="flex items-center justify-between gap-3">
                <div class="space-y-1">
                    <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Informasi Card</p>
                    <h1 class="m-0 text-2xl font-black">Lesson: {{ $lesson->title }}</h1>
                    <p class="m-0 text-sm opacity-75">Content: {{ $content->title }}</p>
                </div>
                <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                    Urutan #{{ $card->order_index }}
                </span>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-2xl border border-white/15 bg-white/10 p-5 shadow-2xl">
                <p class="m-0 text-sm font-semibold mb-3">Blocks</p>
                <div class="space-y-3">
                    @forelse ($blocks as $block)
                        @php
                            $color = $typeColors[$block->type->value ?? $block->type] ?? 'bg-slate-500/20 text-slate-100 border-slate-400/40';
                            $blockType = $block->type->value ?? $block->type;
                        @endphp
                        <div class="rounded-lg border border-white/15 bg-white/5 p-4 space-y-2">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $color }}">
                                        {{ $blockType }}
                                    </span>
                                    <span class="text-xs opacity-70">Urutan #{{ $block->order_index }}</span>
                                </div>
                                <form action="{{ route('blocks.destroy', $block) }}" method="POST" class="inline-flex items-center gap-2" onsubmit="return confirm('Hapus block ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-xs font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                        <i class="fa-solid fa-trash"></i>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            <div class="text-sm leading-relaxed space-y-1">
                                @switch($blockType)
                                    @case('text')
                                        <p class="m-0 opacity-85">{{ \Illuminate\Support\Str::limit($block->data['content'] ?? '', 180) }}</p>
                                        @break
                                    @case('image')
                                    @case('gif')
                                    @case('video')
                                        <p class="m-0 opacity-85">File: {{ $block->data['filename'] ?? 'Tidak ada file' }}</p>
                                        <p class="m-0 text-xs opacity-70">Alt: {{ $block->data['alt'] ?? '-' }}</p>
                                        @break
                                    @case('quiz')
                                        <p class="m-0 font-semibold">{{ $block->data['question'] ?? 'Pertanyaan' }}</p>
                                        <ul class="m-0 list-disc list-inside text-xs opacity-80 space-y-1">
                                            @foreach (($block->data['choices'] ?? []) as $key => $choice)
                                                <li class="{{ ($block->data['answer'] ?? '') === $key ? 'font-semibold text-emerald-200' : '' }}">
                                                    <span class="font-bold">{{ $key }}.</span> {{ $choice }}
                                                </li>
                                            @endforeach
                                        </ul>
                                        <p class="m-0 text-xs opacity-70">Jawaban: {{ $block->data['answer'] ?? '-' }}</p>
                                        @break
                                    @case('code')
                                        <p class="m-0 text-xs uppercase tracking-wide opacity-70">{{ $block->data['language'] ?? 'language' }}</p>
                                        <pre class="rounded-lg bg-black/50 p-3 text-xs overflow-auto"><code>{{ $block->data['code'] ?? '' }}</code></pre>
                                        @break
                                    @default
                                        <p class="m-0 text-xs opacity-70">Data: {{ json_encode($block->data) }}</p>
                                @endswitch
                            </div>
                        </div>
                    @empty
                        <p class="m-0 text-sm opacity-75">Belum ada block.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-dashed border-white/20 bg-white/5 p-5 shadow-2xl space-y-3">
                <p class="m-0 text-sm font-semibold">Tambah Block</p>
                <form action="{{ route('blocks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3" data-block-form>
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

                    <div class="space-y-2" data-fields-for="quiz" class="hidden">
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

                    <div class="space-y-2" data-fields-for="image" class="hidden">
                        <label class="text-xs uppercase tracking-wide opacity-70">File Gambar</label>
                        <input type="file" name="data[file]" accept=".jpg,.jpeg,.png" data-require-for="image"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        <input type="text" name="data[alt]"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                    </div>

                    <div class="space-y-2" data-fields-for="gif" class="hidden">
                        <label class="text-xs uppercase tracking-wide opacity-70">File GIF</label>
                        <input type="file" name="data[file]" accept=".gif" data-require-for="gif"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        <input type="text" name="data[alt]"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                    </div>

                    <div class="space-y-2" data-fields-for="video" class="hidden">
                        <label class="text-xs uppercase tracking-wide opacity-70">File Video (mp4)</label>
                        <input type="file" name="data[file]" accept=".mp4" data-require-for="video"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                        <input type="text" name="data[alt]"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                    </div>

                    <div class="space-y-2" data-fields-for="code" class="hidden">
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
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-block-form]').forEach((form) => {
            const select = form.querySelector('[data-block-type]');
            const sync = () => {
                const type = select.value;
                form.querySelectorAll('[data-fields-for]').forEach((group) => {
                    const show = group.dataset.fieldsFor.split(',').includes(type);
                    group.classList.toggle('hidden', !show);
                });
                form.querySelectorAll('[data-require-for]').forEach((input) => {
                    const required = (input.dataset.requireFor || '').split(',').includes(type);
                    input.required = required;
                });
            };
            select?.addEventListener('change', sync);
            sync();
        });
    });
</script>
@endsection
