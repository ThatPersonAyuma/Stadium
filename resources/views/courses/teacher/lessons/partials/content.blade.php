<div class="rounded-2xl border border-white/12 bg-gradient-to-br from-slate-800/50 via-slate-900/60 to-slate-950/60 p-5 shadow-xl space-y-4" data-content-id="{{ $content->id }}" data-order="{{ $content->order_index }}">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="m-0 text-xs uppercase tracking-[0.2em] text-white/70" data-content-order-label>Content {{ $content->order_index }}</p>
            <h3 class="m-0 text-xl font-black leading-tight" data-content-title>{{ $content->title }}</h3>
        </div>
        <div class="flex items-center gap-2 text-xs uppercase tracking-wide opacity-80">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 border border-white/20">
                <i class="fa-solid fa-layer-group"></i>
                <span data-card-count>{{ $content->cards->count() }}</span> Cards
            </span>
            <button type="button" data-toggle-content-edit data-target="content-edit-{{ $content->id }}"
                    class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-3 py-2 font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-pen"></i>
                Edit
            </button>
            <button type="button" data-delete-content data-url="{{ route('contents.destroy', $content) }}"
                    class="inline-flex items-center gap-1 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                <i class="fa-solid fa-trash"></i>
                Hapus
            </button>
        </div>
    </div>

    <form id="content-edit-{{ $content->id }}" action="{{ route('contents.update', $content) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 rounded-xl border border-white/10 bg-white/5 p-4 hidden" data-content-edit>
        @csrf
        @method('PUT')
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
        <div class="md:col-span-2 space-y-2">
            <label class="text-xs uppercase tracking-wide opacity-70">Judul Content</label>
            <input type="text" name="title" value="{{ $content->title }}" required
                   class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
        </div>
        <div class="space-y-2">
            <label class="text-xs uppercase tracking-wide opacity-70">Urutan</label>
            <input type="number" name="order_index" min="1" value="{{ $content->order_index }}"
                   class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" required>
        </div>
        <div class="md:col-span-3 flex flex-wrap gap-2 justify-end">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan
            </button>
            <button type="button" data-toggle-content-edit data-target="content-edit-{{ $content->id }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-xmark"></i>
                Batal
            </button>
        </div>
    </form>

    <div class="space-y-4" data-card-list>
        @forelse ($content->cards as $card)
            @include('courses.teacher.lessons.partials.card', [
                'card' => $card,
                'content' => $content,
                'lesson' => $lesson,
                'course' => $course,
            ])
        @empty
            <p class="m-0 text-sm opacity-75" data-empty-card>Belum ada card untuk content ini.</p>
        @endforelse
    </div>

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
