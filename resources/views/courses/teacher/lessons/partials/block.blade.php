@php
    $blockType = $block->type->value ?? $block->type;
    $color = $typeColors[$blockType] ?? 'bg-slate-500/20 text-slate-100 border-slate-400/40';
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
            {{-- @case('image')
            @case('gif')
            @case('video')
                <p class="m-0 opacity-85">File: {{ $block->data['filename'] ?? 'Tidak ada file' }}</p>
                <p class="m-0 text-xs opacity-70">Alt: {{ $block->data['alt'] ?? '-' }}</p>
                @break --}}
            @case('image')
            @case('gif')
            @case('video')

            <div style="display: flex; flex-direction: column; gap: 6px;">

                {{-- File Name --}}
                <p style="margin:0; opacity:0.85;">
                    File: {{ $block->data['filename'] ?? 'Tidak ada file' }}
                </p>

                {{-- Alt Text --}}
                <p style="margin:0; font-size: 12px; opacity:0.70;">
                    Alt: {{ $block->data['alt'] ?? '-' }}
                </p>

                {{-- Preview Container --}}
                <div 
                    style="
                        width: 100%; 
                        max-height: 280px; 
                        overflow: hidden; 
                        border-radius: 6px;
                        background: rgba(0,0,0,0.05);
                        display: flex; 
                        justify-content: center; 
                        align-items: center;
                    "
                >
                    @if ($blockType === 'video')
                        <video 
                            src="{{ asset(App\Helpers\FileHelper::getBlockUrl($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}"
                            controls
                            style="
                                width: 100%;
                                height: 100%;
                                object-fit: contain;
                                border-radius: 6px;
                            "
                        ></video>
                    @else
                        <img 
                            src="{{ asset(App\Helpers\FileHelper::getBlockUrl($course->id, $lesson->id, $content->id, $card->id, $block->id)) }}"
                            alt="{{ $block->data['alt'] ?? '' }}"
                            style="
                                width: 100%;
                                height: 100%;
                                object-fit: contain;
                                border-radius: 6px;
                            "
                        >
                    @endif
                </div>

            </div>

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
        <input type="hidden" name="type" value="{{ $block->type->value }}">
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
</div>
