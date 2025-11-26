@extends('layouts.dashboard')
@section('title', 'Kelola Lesson')

@section('content')
@php
    $stats = $stats ?? ['contents' => 0, 'cards' => 0, 'blocks' => 0];
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
        <x-dashboard-header title="Kelola Lesson" subtitle="{{ $course->title }} · Lesson {{ $lesson->order_index }}" />
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('teacher.courses.show', $course) }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Course
            </a>
            <a href="{{ route('teacher.courses.index') }}"
               class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-4 py-2.5 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                <i class="fa-solid fa-list"></i>
                Daftar Course
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Lesson</p>
                <p class="m-0 text-2xl font-black leading-tight">{{ $lesson->title }}</p>
                <p class="m-0 text-sm opacity-80">{{ $lesson->description ?: 'Belum ada deskripsi lesson.' }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Contents</p>
                <p class="m-0 text-2xl font-black">{{ $stats['contents'] }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Cards</p>
                <p class="m-0 text-2xl font-black">{{ $stats['cards'] }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg md:col-span-3">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Blocks</p>
                <p class="m-0 text-2xl font-black">{{ $stats['blocks'] }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Informasi Lesson</p>
                    <h2 class="m-0 text-lg font-semibold">Perbarui lesson</h2>
                </div>
                <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                    Urutan #{{ $lesson->order_index }}
                </span>
            </div>
            <form action="{{ route('teacher.courses.lessons.update', [$course, $lesson]) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @csrf
                @method('PATCH')
                <div class="md:col-span-2 space-y-2">
                    <label class="text-xs uppercase tracking-wide opacity-70">Judul</label>
                    <input type="text" name="title" value="{{ $lesson->title }}" required
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-wide opacity-70">Urutan</label>
                    <input type="number" name="order_index" min="1" value="{{ $lesson->order_index }}"
                           class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                </div>
                <div class="md:col-span-3 space-y-2">
                    <label class="text-xs uppercase tracking-wide opacity-70">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">{{ $lesson->description }}</textarea>
                </div>
                <div class="md:col-span-3 flex flex-wrap gap-2 justify-end">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 text-sm font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                        Simpan Lesson
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-6" id="lesson-contents">
            @forelse ($lesson->contents as $content)
                <div class="rounded-2xl border border-white/12 bg-white/5 p-5 shadow-lg space-y-4" data-content-id="{{ $content->id }}">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="m-0 text-xs uppercase tracking-[0.2em] text-white/70">Content {{ $content->order_index }}</p>
                            <h3 class="m-0 text-xl font-black leading-tight">{{ $content->title }}</h3>
                        </div>
                        <span class="text-xs uppercase tracking-wide opacity-75">{{ $content->cards->count() }} Cards</span>
                    </div>

                    <div class="space-y-4" data-card-list>
                        @forelse ($content->cards as $card)
                            <div class="rounded-xl border border-white/10 bg-white/5 p-4 shadow-md space-y-3" data-card-id="{{ $card->id }}" data-order="{{ $card->order_index }}">
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <div class="flex items-center gap-2">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-semibold">#{{ $card->order_index }}</span>
                                        <div>
                                            <p class="m-0 text-xs uppercase tracking-wide opacity-70">Card</p>
                                            <p class="m-0 text-sm opacity-80">{{ $card->blocks->count() }} blocks</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs uppercase tracking-wide opacity-75">
                                        <span>Content ID: {{ $content->id }}</span>
                                        <a href="{{ route('cards.show', $card) }}" class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-2 py-1 text-[11px] font-semibold text-white transition hover:-translate-y-0.5">
                                            <i class="fa-solid fa-eye"></i>
                                            Detail Card
                                        </a>
                                        <button type="button" data-delete-card data-delete-url="{{ route('cards.destroy', $card) }}"
                                                class="inline-flex items-center gap-1 rounded-lg border border-rose-200/40 bg-rose-500/20 px-2 py-1 text-[11px] font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                            <i class="fa-solid fa-trash"></i>
                                            Hapus Card
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-3" data-block-list>
                                    @forelse ($card->blocks as $block)
                                        @php
                                            $color = $typeColors[$block->type->value ?? $block->type] ?? 'bg-slate-500/20 text-slate-100 border-slate-400/40';
                                            $blockType = $block->type->value ?? $block->type;
                                        @endphp
                                        <div class="rounded-lg border px-4 py-3 bg-white/10 border-white/10 space-y-2" data-block-id="{{ $block->id }}" data-order="{{ $block->order_index }}">
                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $color }}">
                                                        {{ $blockType }}
                                                    </span>
                                                    <span class="text-xs opacity-70">Urutan #{{ $block->order_index }}</span>
                                                </div>
                                                <div class="flex flex-wrap gap-2">
                                                    <button type="button" data-toggle-edit data-target="edit-block-{{ $block->id }}"
                                                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                        Edit
                                                    </button>
                                                    <button type="button" data-delete-block data-url="{{ route('blocks.destroy', $block) }}"
                                                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-xs font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                                        <i class="fa-solid fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="space-y-1 text-sm leading-relaxed">
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

                                            <form id="edit-block-{{ $block->id }}" action="{{ route('blocks.update', $block) }}" method="POST" enctype="multipart/form-data" class="space-y-3 rounded-lg border border-white/10 bg-white/5 p-3 mt-2 hidden" data-block-form>
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="card_id" value="{{ $card->id }}">
                                                <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                                <input type="hidden" name="content_id" value="{{ $content->id }}">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div class="space-y-2">
                                                        <label class="text-xs uppercase tracking-wide opacity-70">Urutan Block</label>
                                                        <input type="number" name="order_index" min="1" value="{{ $block->order_index }}"
                                                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                                    </div>
                                                    <div class="space-y-2 md:col-span-2">
                                                        <label class="text-xs uppercase tracking-wide opacity-70">Tipe</label>
                                                        <input type="text" value="{{ $blockType }}" disabled
                                                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white/70">
                                                <select name="type" data-block-type class="hidden">
                                                    <option value="{{ $blockType }}" selected>{{ $blockType }}</option>
                                                </select>
                                                </div>
                                            </div>

                                                <div class="space-y-2" data-fields-for="text">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">Konten</label>
                                                    <textarea name="data[content]" rows="3" data-require-for="text"
                                                              class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">{{ $block->data['content'] ?? '' }}</textarea>
                                                </div>

                                                <div class="space-y-2" data-fields-for="quiz">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">Pertanyaan</label>
                                                    <input type="text" name="data[question]" value="{{ $block->data['question'] ?? '' }}" data-require-for="quiz"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        @foreach (['A','B','C','D'] as $opt)
                                                            <div class="space-y-1">
                                                                <label class="text-xs uppercase tracking-wide opacity-70">Pilihan {{ $opt }}</label>
                                                                <input type="text" name="data[choices][{{ $opt }}]" value="{{ $block->data['choices'][$opt] ?? '' }}" data-require-for="quiz"
                                                                       class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                                        <div class="space-y-1">
                                                            <label class="text-xs uppercase tracking-wide opacity-70">Jawaban</label>
                                                            <select name="data[answer]" data-require-for="quiz"
                                                                    class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                                                <option value="" disabled {{ empty($block->data['answer']) ? 'selected' : '' }}>Pilih jawaban</option>
                                                                @foreach (['A','B','C','D'] as $opt)
                                                                    <option value="{{ $opt }}" {{ ($block->data['answer'] ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="space-y-1">
                                                            <label class="text-xs uppercase tracking-wide opacity-70">Penjelasan</label>
                                                            <textarea name="data[explanation]" rows="2" data-require-for="quiz"
                                                                      class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">{{ $block->data['explanation'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="space-y-2" data-fields-for="image">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">File Gambar</label>
                                                    <input type="file" name="data[file]" accept=".jpg,.jpeg,.png" data-require-for="image"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                                    <input type="text" name="data[alt]" value="{{ $block->data['alt'] ?? '' }}"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text">
                                                </div>

                                                <div class="space-y-2" data-fields-for="gif">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">File GIF</label>
                                                    <input type="file" name="data[file]" accept=".gif" data-require-for="gif"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                                    <input type="text" name="data[alt]" value="{{ $block->data['alt'] ?? '' }}"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text">
                                                </div>

                                                <div class="space-y-2" data-fields-for="video">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">File Video (mp4)</label>
                                                    <input type="file" name="data[file]" accept=".mp4" data-require-for="video"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                                    <input type="text" name="data[alt]" value="{{ $block->data['alt'] ?? '' }}"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text">
                                                </div>

                                                <div class="space-y-2" data-fields-for="code">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">Bahasa</label>
                                                    <input type="text" name="data[language]" value="{{ $block->data['language'] ?? '' }}" data-require-for="code"
                                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                                                    <label class="text-xs uppercase tracking-wide opacity-70">Kode</label>
                                                    <textarea name="data[code]" rows="3" data-require-for="code"
                                                              class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">{{ $block->data['code'] ?? '' }}</textarea>
                                                </div>

                                                <div class="flex flex-wrap gap-2 justify-end">
                                                    <button type="submit"
                                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                                                        Simpan Block
                                                    </button>
                                                    <button type="button" data-toggle-edit data-target="edit-block-{{ $block->id }}"
                                                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </form>

                                            <form id="delete-block-{{ $block->id }}" action="{{ route('blocks.destroy', $block) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    @empty
                                        <p class="m-0 text-sm opacity-75">Belum ada block di card ini.</p>
                                    @endforelse
                                </div>

                                <div class="rounded-lg border border-dashed border-white/20 bg-white/5 p-4 space-y-3">
                                    <p class="m-0 text-sm font-semibold">Tambah Block ke Card #{{ $card->order_index }}</p>
                                    <form action="{{ route('blocks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3" data-block-form>
                                        @csrf
                                        <input type="hidden" name="card_id" value="{{ $card->id }}">
                                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="content_id" value="{{ $content->id }}">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                            <div class="space-y-2">
                                                <label class="text-xs uppercase tracking-wide opacity-70">Urutan Block</label>
                                                <input type="number" name="order_index" min="1" value="{{ ($card->blocks->max('order_index') ?? 0) + 1 }}"
                                                       class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                            </div>
                                            <div class="md:col-span-3 space-y-2">
                                                <label class="text-xs uppercase tracking-wide opacity-70">Tipe Block</label>
                                                <select name="type" data-block-type
                                                        class="w-full rounded-xl border border-white/25 bg-slate-900/70 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
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

                                        <div class="space-y-2" data-fields-for="quiz">
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
                                                            class="w-full rounded-xl border border-white/25 bg-slate-900/70 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
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

                                        <div class="space-y-2" data-fields-for="image">
                                            <label class="text-xs uppercase tracking-wide opacity-70">File Gambar</label>
                                            <input type="file" name="data[file]" accept=".jpg,.jpeg,.png" data-require-for="image"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                            <input type="text" name="data[alt]"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                                        </div>

                                        <div class="space-y-2" data-fields-for="gif">
                                            <label class="text-xs uppercase tracking-wide opacity-70">File GIF</label>
                                            <input type="file" name="data[file]" accept=".gif" data-require-for="gif"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                            <input type="text" name="data[alt]"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                                        </div>

                                        <div class="space-y-2" data-fields-for="video">
                                            <label class="text-xs uppercase tracking-wide opacity-70">File Video (mp4)</label>
                                            <input type="file" name="data[file]" accept=".mp4" data-require-for="video"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                            <input type="text" name="data[alt]"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Alt text (opsional)">
                                        </div>

                                        <div class="space-y-2" data-fields-for="code">
                                            <label class="text-xs uppercase tracking-wide opacity-70">Bahasa</label>
                                            <input type="text" name="data[language]" data-require-for="code"
                                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Contoh: php, js">
                                            <label class="text-xs uppercase tracking-wide opacity-70">Kode</label>
                                            <textarea name="data[code]" rows="3" data-require-for="code"
                                                      class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" placeholder="Tempelkan kode di sini"></textarea>
                                        </div>

                                        <div class="flex flex-wrap gap-2 justify-end">
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                                                Tambah Block
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="m-0 text-sm opacity-75">Belum ada card untuk content ini.</p>
                        @endforelse
                        <form action="{{ route('cards.store') }}" method="POST" class="rounded-xl border border-dashed border-white/20 bg-white/5 p-4 space-y-3" data-card-create data-content-id="{{ $content->id }}">
                            @csrf
                            <input type="hidden" name="content_id" value="{{ $content->id }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-xs uppercase tracking-wide opacity-70">Urutan Card</label>
                                    <input type="number" name="order_index" min="1" value="{{ ($content->cards->max('order_index') ?? 0) + 1 }}"
                                           class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none" required>
                                </div>
                                <div class="flex items-end justify-end">
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-sm font-semibold shadow-md transition hover:-translate-y-0.5">
                                        <i class="fa-solid fa-square-plus"></i>
                                        Tambah Card
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <p class="m-0 text-sm opacity-80">Belum ada content di lesson ini.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const $ = window.jQuery;
        if (!$) return;

        const token = '{{ csrf_token() }}';

        const syncFields = (form) => {
            const select = form.querySelector('[data-block-type]');
            if (!select) return;
            const type = select.value;
            const visibleTypes = type.split(',');

            form.querySelectorAll('[data-fields-for]').forEach((group) => {
                const handles = group.dataset.fieldsFor.split(',');
                const show = handles.some((t) => visibleTypes.includes(t));
                group.classList.toggle('hidden', !show);
            });

            form.querySelectorAll('[data-require-for]').forEach((input) => {
                const requiredTypes = (input.dataset.requireFor || '').split(',');
                input.required = requiredTypes.includes(type);
            });
        };

        document.querySelectorAll('[data-block-form]').forEach((form) => {
            syncFields(form);
            const select = form.querySelector('[data-block-type]');
            if (select) {
                select.addEventListener('change', () => syncFields(form));
            }
        });

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

        const buildBlockNode = (block) => {
            const color = {
                text: 'bg-emerald-500/20 text-emerald-100 border-emerald-400/40',
                image: 'bg-sky-500/20 text-sky-100 border-sky-400/40',
                gif: 'bg-indigo-500/20 text-indigo-100 border-indigo-400/40',
                video: 'bg-amber-500/20 text-amber-50 border-amber-400/40',
                quiz: 'bg-rose-500/20 text-rose-50 border-rose-400/40',
                code: 'bg-slate-500/20 text-slate-100 border-slate-400/40',
            }[block.type] || 'bg-slate-500/20 text-slate-100 border-slate-400/40';
            return $(`
                <div class="rounded-lg border px-4 py-3 bg-white/10 border-white/10 space-y-2" data-block-id="${block.id}" data-order="${block.order_index}">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide ${color}">
                                ${block.type}
                            </span>
                            <span class="text-xs opacity-70">Urutan #${block.order_index}</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-xs font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30" data-delete-block data-url="${block.delete_url}">
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>
                    <div class="text-sm opacity-80">Block baru ditambahkan.</div>
                </div>
            `);
        };

        const insertSorted = (list, node) => {
            const newOrder = parseInt(node.data('order'), 10);
            let placed = false;
            list.children('[data-order]').each(function() {
                const existing = parseInt($(this).data('order'), 10);
                if (newOrder < existing) {
                    $(this).before(node);
                    placed = true;
                    return false;
                }
            });
            if (!placed) list.append(node);
        };

        $('[data-card-create]').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const contentWrap = form.closest('[data-content-id]');
            const cardList = contentWrap.find('[data-card-list]');
            const desiredOrder = parseInt(form.find('input[name="order_index"]').val() || '0', 10);
            if (desiredOrder) {
                const duplicate = cardList.children('[data-card-id]').filter(function() {
                    return parseInt($(this).data('order'), 10) === desiredOrder;
                }).length > 0;
                if (duplicate) {
                    toast('Urutan card sudah digunakan.', false);
                    return;
                }
            }
            const formData = new FormData(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: (data) => {
                    const card = data.card;
                    const node = $(`
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4 shadow-md space-y-3" data-card-id="${card.id}" data-order="${card.order_index}">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-semibold">#${card.order_index}</span>
                                    <div>
                                        <p class="m-0 text-xs uppercase tracking-wide opacity-70">Card</p>
                                        <p class="m-0 text-sm opacity-80">0 blocks</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-xs uppercase tracking-wide opacity-75">
                                    <span>Content ID: ${contentWrap.data('content-id')}</span>
                                    <a href="${data.detail_url}" class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-2 py-1 text-[11px] font-semibold text-white transition hover:-translate-y-0.5">
                                        <i class="fa-solid fa-eye"></i>
                                        Detail Card
                                    </a>
                                    <button type="button" data-delete-card data-delete-url="${data.delete_url}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-rose-200/40 bg-rose-500/20 px-2 py-1 text-[11px] font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                        <i class="fa-solid fa-trash"></i>
                                        Hapus Card
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-3" data-block-list></div>
                            <form action="{{ route('blocks.store') }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-dashed border-white/20 bg-white/5 p-4 space-y-3" data-block-create data-card-id="${card.id}">
                                @csrf
                                <input type="hidden" name="card_id" value="${card.id}">
                                <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <input type="hidden" name="content_id" value="${contentWrap.data('content-id')}">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div class="space-y-2">
                                        <label class="text-xs uppercase tracking-wide opacity-70">Urutan Block</label>
                                        <input type="number" name="order_index" min="1" value="1"
                                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
                                    </div>
                                    <div class="md:col-span-3 space-y-2">
                                        <label class="text-xs uppercase tracking-wide opacity-70">Tipe Block</label>
                                        <select name="type" data-block-type
                                                class="w-full rounded-xl border border-white/25 bg-slate-900/70 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
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
                                                    class="w-full rounded-xl border border-white/25 bg-slate-900/70 px-3 py-2 text-white focus:border-white/60 focus:outline-none">
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
                    `);
                    insertSorted(cardList, node);
                    form.trigger('reset');
                    toast(data.message || 'Card ditambahkan');
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal menambah card';
                    toast(msg, false);
                }
            });
        });

        $(document).on('click', '[data-delete-card]', function() {
            const btn = $(this);
            const url = btn.data('delete-url');
            const cardWrap = btn.closest('[data-card-id]');
            const proceed = () => {
                $.ajax({
                    url,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                    success: () => {
                        cardWrap.remove();
                        toast('Card dihapus');
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus card';
                        toast(msg, false);
                    }
                });
            };
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus card?',
                    text: 'Aksi ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    reverseButtons: true,
                }).then((res) => res.isConfirmed && proceed());
            } else if (confirm('Hapus card ini?')) {
                proceed();
            }
        });

        $(document).on('submit', '[data-block-create]', function(e) {
            e.preventDefault();
            const form = $(this);
            const blockList = form.closest('[data-card-id]').find('[data-block-list]');
            const desiredOrder = parseInt(form.find('input[name="order_index"]').val() || '0', 10);
            if (desiredOrder) {
                const duplicate = blockList.children('[data-block-id]').filter(function() {
                    return parseInt($(this).data('order'), 10) === desiredOrder;
                }).length > 0;
                if (duplicate) {
                    toast('Urutan block sudah digunakan.', false);
                    return;
                }
            }
            const formData = new FormData(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: (data) => {
                    // Block controller returns limited data; fallback to reload for accuracy.
                    toast('Block ditambahkan');
                    setTimeout(() => window.location.reload(), 300);
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal menambah block';
                    toast(msg, false);
                }
            });
        });

        $(document).on('click', '[data-delete-block]', function() {
            const btn = $(this);
            const url = btn.data('url');
            const blockWrap = btn.closest('[data-block-id]');
            const proceed = () => {
                $.ajax({
                    url,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                    success: () => {
                        blockWrap.remove();
                        toast('Block dihapus');
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
                    text: 'Aksi ini tidak dapat dibatalkan.',
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

        document.querySelectorAll('[data-toggle-edit]').forEach((btn) => {
            const targetId = btn.dataset.target;
            const target = document.getElementById(targetId);
            if (!target) return;
            btn.addEventListener('click', () => {
                target.classList.toggle('hidden');
                if (!target.classList.contains('hidden')) {
                    const select = target.querySelector('[data-block-type]');
                    if (select) select.dispatchEvent(new Event('change'));
                }
            });
        });
    });
</script>
@endsection
