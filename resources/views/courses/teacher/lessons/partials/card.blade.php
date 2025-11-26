<div class="rounded-xl border border-white/10 bg-gradient-to-br from-slate-800/60 via-slate-900/60 to-slate-950/60 p-4 shadow-lg space-y-3" data-card-id="{{ $card->id }}" data-order="{{ $card->order_index }}">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-sm font-semibold border border-white/20" data-card-order-number>#{{ $card->order_index }}</span>
            <div>
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Card</p>
                <p class="m-0 text-sm opacity-80" data-card-order-text>Order {{ $card->order_index }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-[11px] uppercase tracking-wide opacity-75">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 border border-white/20">
                Content ID: {{ $content->id }}
            </span>
            <a href="{{ route('cards.show', $card) }}" class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-2 py-1 font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-eye"></i>
                Detail Card
            </a>
            <button type="button" data-toggle-card-edit data-target="card-edit-{{ $card->id }}"
                    class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-2 py-1 font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-pen"></i>
                Edit
            </button>
            <button type="button" data-delete-card data-delete-url="{{ route('cards.destroy', $card) }}"
                    class="inline-flex items-center gap-1 rounded-lg border border-rose-200/40 bg-rose-500/20 px-2 py-1 font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                <i class="fa-solid fa-trash"></i>
                Hapus Card
            </button>
        </div>
    </div>

    <p class="m-0 text-sm opacity-70">Kelola block pada halaman detail card.</p>

    <form id="card-edit-{{ $card->id }}" action="{{ route('cards.update', $card) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 rounded-xl border border-white/10 bg-white/5 p-4 hidden" data-card-edit>
        @csrf
        @method('PUT')
        <div class="space-y-2">
            <label class="text-xs uppercase tracking-wide opacity-70">Urutan</label>
            <input type="number" name="order_index" min="1" value="{{ $card->order_index }}"
                   class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" required>
        </div>
        <div class="md:col-span-2 flex flex-wrap gap-2 justify-end items-end">
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Card
            </button>
            <button type="button" data-toggle-card-edit data-target="card-edit-{{ $card->id }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                <i class="fa-solid fa-xmark"></i>
                Batal
            </button>
        </div>
    </form>
</div>
