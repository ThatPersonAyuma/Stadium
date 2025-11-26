@extends('layouts.dashboard')
@section('title', 'Kelola Lesson')

@section('content')
@php
    $stats = $stats ?? ['contents' => 0, 'cards' => 0, 'blocks' => 0];
@endphp
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-6xl space-y-10">
        <x-dashboard-header title="Kelola Lesson" subtitle="{{ $course->title }} - Lesson {{ $lesson->order_index }}" />
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
                <p class="m-0 text-2xl font-black" data-stat-contents>{{ $stats['contents'] }}</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 p-4 shadow-lg">
                <p class="m-0 text-xs uppercase tracking-wide opacity-70">Cards</p>
                <p class="m-0 text-2xl font-black" data-stat-cards>{{ $stats['cards'] }}</p>
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

        @php
            $nextContentOrder = ($lesson->contents->max('order_index') ?? 0) + 1;
        @endphp

        <div class="space-y-6">
            <div class="rounded-2xl border border-dashed border-white/20 bg-gradient-to-r from-slate-800/70 via-slate-900/70 to-slate-950/70 p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="m-0 text-xs uppercase tracking-[0.15em] opacity-70">Content Baru</p>
                        <h2 class="m-0 text-lg font-semibold">Tambahkan content untuk lesson ini</h2>
                    </div>
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide inline-flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Content
                    </span>
                </div>
                <form action="{{ route('contents.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3" data-content-create>
                    @csrf
                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs uppercase tracking-wide opacity-70">Judul Content</label>
                        <input type="text" name="title" placeholder="Contoh: Pendahuluan" required
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide opacity-70">Urutan</label>
                        <input type="number" name="order_index" min="1" value="{{ $nextContentOrder }}"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" required>
                    </div>
                    <div class="md:col-span-3 flex flex-wrap gap-2 justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 text-sm font-semibold shadow-lg transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white">
                            <i class="fa-solid fa-circle-plus"></i>
                            Tambah Content
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6" id="lesson-contents">
                @forelse ($lesson->contents as $content)
                    @include('courses.teacher.lessons.partials.content', [
                        'content' => $content,
                        'course' => $course,
                        'lesson' => $lesson,
                    ])
                @empty
                    <p class="m-0 text-sm opacity-80" data-content-empty>Belum ada content di lesson ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    const initLessonPage = ($) => {
        if (!$) return;

        const token = '{{ csrf_token() }}';

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

        const statContents = document.querySelector('[data-stat-contents]');
        const statCards = document.querySelector('[data-stat-cards]');
        const updateStat = (node, delta = 0) => {
            if (!node) return;
            const current = parseInt(node.textContent || '0', 10) || 0;
            node.textContent = Math.max(0, current + delta);
        };

        const contentList = $('#lesson-contents');
        const removeEmptyContentMessage = () => contentList.find('[data-content-empty]').remove();
        const relabelContents = () => {
            contentList.children('[data-content-id]').each(function() {
                const order = $(this).data('order');
                $(this).find('[data-content-order-label]').text(`Content ${order}`);
            });
        };
        const nextContentOrder = () => {
            const orders = contentList.children('[data-content-id]').map(function() {
                return parseInt($(this).data('order'), 10) || 0;
            }).get();
            const maxOrder = orders.length ? Math.max(...orders) : 0;
            return maxOrder + 1;
        };

        const buildContentNode = (payload = {}) => {
            const cardCount = payload.card_count ?? payload.cards_count ?? 0;
            const order = payload.order_index ?? 1;
            const courseId = payload.meta?.course_id ?? {{ $course->id }};
            const lessonId = payload.meta?.lesson_id ?? {{ $lesson->id }};
            const updateUrl = payload.urls?.update ?? '';
            const deleteUrl = payload.urls?.delete ?? '';
            return $(
                `<div class="rounded-2xl border border-white/12 bg-white/5 p-5 shadow-lg space-y-4" data-content-id="${payload.id}" data-order="${order}">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="m-0 text-xs uppercase tracking-[0.2em] text-white/70" data-content-order-label>Content ${order}</p>
                            <h3 class="m-0 text-xl font-black leading-tight" data-content-title>${payload.title ?? 'Content'}</h3>
                        </div>
                        <div class="flex items-center gap-2 text-xs uppercase tracking-wide opacity-75">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 border border-white/15">
                                <i class="fa-solid fa-layer-group"></i>
                                <span data-card-count>${cardCount}</span> Cards
                            </span>
                            <button type="button" data-toggle-content-edit data-target="content-edit-${payload.id}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-[11px] font-semibold text-white transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>
                            <button type="button" data-delete-content data-url="${deleteUrl}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-rose-200/40 bg-rose-500/20 px-3 py-2 text-[11px] font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </div>

                    <form id="content-edit-${payload.id}" action="${updateUrl}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 rounded-xl border border-white/10 bg-white/5 p-4 hidden" data-content-edit>
                        <input type="hidden" name="_token" value="${token}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="course_id" value="${courseId}">
                        <input type="hidden" name="lesson_id" value="${lessonId}">
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs uppercase tracking-wide opacity-70">Judul Content</label>
                            <input type="text" name="title" value="${payload.title ?? ''}" required
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs uppercase tracking-wide opacity-70">Urutan</label>
                            <input type="number" name="order_index" min="1" value="${order}"
                                   class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" required>
                        </div>
                        <div class="md:col-span-3 flex flex-wrap gap-2 justify-end">
                            <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan Perubahan
                            </button>
                            <button type="button" data-toggle-content-edit data-target="content-edit-${payload.id}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                                <i class="fa-solid fa-xmark"></i>
                                Batal
                            </button>
                        </div>
                    </form>

                    <div class="space-y-3" data-card-list>
                        <p class="m-0 text-sm opacity-75" data-empty-card>Belum ada card untuk content ini.</p>
                    </div>
                    <form action="{{ route('cards.store') }}" method="POST" class="rounded-xl border border-dashed border-white/20 bg-white/5 p-4 space-y-3" data-card-create data-content-id="${payload.id}">
                        <input type="hidden" name="_token" value="${token}">
                        <input type="hidden" name="content_id" value="${payload.id}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="text-xs uppercase tracking-wide opacity-70">Urutan Card</label>
                                <input type="number" name="order_index" min="1" value="1"
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
                </div>`
            );
        };

        const buildCardNode = (card, contentId, detailUrl, deleteUrl, updateUrl) => $(
            `<div class="rounded-xl border border-white/10 bg-white/5 p-4 shadow-md space-y-3" data-card-id="${card.id}" data-order="${card.order_index}">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm font-semibold" data-card-order-number>#${card.order_index}</span>
                        <div>
                            <p class="m-0 text-xs uppercase tracking-wide opacity-70">Card</p>
                            <p class="m-0 text-sm opacity-80" data-card-order-text>Order ${card.order_index}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs uppercase tracking-wide opacity-75">
                        <span>Content ID: ${contentId}</span>
                        <a href="${detailUrl}" class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-2 py-1 text-[11px] font-semibold text-white transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-eye"></i>
                            Detail Card
                        </a>
                        <button type="button" data-toggle-card-edit data-target="card-edit-${card.id}"
                                class="inline-flex items-center gap-1 rounded-lg border border-white/25 bg-white/10 px-2 py-1 text-[11px] font-semibold text-white transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-pen"></i>
                            Edit
                        </button>
                        <button type="button" data-delete-card data-delete-url="${deleteUrl}"
                                class="inline-flex items-center gap-1 rounded-lg border border-rose-200/40 bg-rose-500/20 px-2 py-1 text-[11px] font-semibold text-rose-50 transition hover:-translate-y-0.5 hover:bg-rose-500/30">
                            <i class="fa-solid fa-trash"></i>
                            Hapus Card
                        </button>
                    </div>
                </div>

                <form id="card-edit-${card.id}" action="${updateUrl}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 rounded-xl border border-white/10 bg-white/5 p-4 hidden" data-card-edit>
                    <input type="hidden" name="_token" value="${token}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-wide opacity-70">Urutan</label>
                        <input type="number" name="order_index" min="1" value="${card.order_index}"
                               class="w-full rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-white placeholder-white/60 focus:border-white/60 focus:outline-none" required>
                    </div>
                    <div class="md:col-span-2 flex flex-wrap gap-2 justify-end items-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-slate-900 px-3 py-2 text-xs font-semibold shadow-md transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Simpan Card
                        </button>
                        <button type="button" data-toggle-card-edit data-target="card-edit-${card.id}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/25 bg-white/10 px-3 py-2 text-xs font-semibold text-white transition hover:-translate-y-0.5">
                            <i class="fa-solid fa-xmark"></i>
                            Batal
                        </button>
                    </div>
                </form>
            </div>`
        );

        const relabelCards = (cardList) => {
            const cards = cardList.children('[data-card-id]').sort((a, b) => {
                const aOrder = parseInt($(a).data('order'), 10) || 0;
                const bOrder = parseInt($(b).data('order'), 10) || 0;
                return aOrder - bOrder;
            });
            cards.each(function() {
                const order = parseInt($(this).data('order'), 10) || 0;
                $(this).find('[data-card-order-number]').text(`#${order}`);
                $(this).find('[data-card-order-text]').text(`Order ${order}`);
            });
        };

        const rebalanceCardOrders = (cardList, target, oldOrder, newOrder) => {
            cardList.children('[data-card-id]').each(function() {
                if (this === target[0]) return;
                const current = parseInt($(this).data('order'), 10) || 0;
                if (oldOrder > newOrder && current >= newOrder && current < oldOrder) {
                    const next = current + 1;
                    $(this).data('order', next).attr('data-order', next);
                } else if (oldOrder < newOrder && current <= newOrder && current > oldOrder) {
                    const next = current - 1;
                    $(this).data('order', next).attr('data-order', next);
                }
            });
            target.data('order', newOrder).attr('data-order', newOrder);
            insertSorted(cardList, target);
            relabelCards(cardList);
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

        $('[data-content-create]').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: (res) => {
                    const contentData = { ...res.content, urls: res.urls, meta: res.meta };
                    const node = buildContentNode(contentData);
                    removeEmptyContentMessage();
                    insertSorted(contentList, node);
                    relabelContents();
                    updateStat(statContents, 1);
                    toast(res.message || 'Content ditambahkan');
                    form[0].reset();
                    form.find('input[name="order_index"]').val(nextContentOrder());
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || xhr.responseJSON?.errors?.title?.[0] || 'Gagal menambah content';
                    toast(msg, false);
                },
            });
        });

        $(document).on('submit', '[data-content-edit]', function(e) {
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: (res) => {
                    const wrapper = form.closest('[data-content-id]').detach();
                    const data = { ...res.content, meta: res.meta };
                    wrapper.data('order', data.order_index);
                    wrapper.attr('data-order', data.order_index);
                    wrapper.find('[data-content-title]').text(data.title);
                    wrapper.find('[data-content-order-label]').text(`Content ${data.order_index}`);
                    form.find('input[name="order_index"]').val(data.order_index);
                    insertSorted(contentList, wrapper);
                    form.addClass('hidden');
                    toast(res.message || 'Content diperbarui');
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal memperbarui content';
                    toast(msg, false);
                },
            });
        });

        $(document).on('click', '[data-toggle-content-edit]', function() {
            const target = document.getElementById(this.dataset.target);
            if (!target) return;
            target.classList.toggle('hidden');
            if (!target.classList.contains('hidden')) {
                target.querySelector('input[name="title"]')?.focus();
            }
        });

        $(document).on('click', '[data-delete-content]', function() {
            const btn = $(this);
            const url = btn.data('url');
            const wrapper = btn.closest('[data-content-id]');
            const proceed = () => {
                $.ajax({
                    url,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                    success: (res) => {
                        const cardCount = wrapper.find('[data-card-id]').length;
                        wrapper.remove();
                        updateStat(statContents, -1);
                        updateStat(statCards, -cardCount);
                        if (!contentList.children('[data-content-id]').length) {
                            contentList.append('<p class="m-0 text-sm opacity-80" data-content-empty>Belum ada content di lesson ini.</p>');
                        }
                        toast(res.message || 'Content dihapus');
                    },
                    error: (xhr) => {
                        const msg = xhr.responseJSON?.message || 'Gagal menghapus content';
                        toast(msg, false);
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus content?',
                    text: 'Seluruh card di dalamnya ikut terhapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#ef4444',
                    reverseButtons: true,
                }).then((res) => res.isConfirmed && proceed());
            } else if (confirm('Hapus content ini beserta card di dalamnya?')) {
                proceed();
            }
        });

        $(document).on('submit', '[data-card-create]', function(e) {
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
                    cardList.find('[data-empty-card]').remove();
                    const node = buildCardNode(card, contentWrap.data('content-id'), data.detail_url, data.delete_url, data.update_url);
                    insertSorted(cardList, node);
                    relabelCards(cardList);
                    const badge = contentWrap.find('[data-card-count]');
                    if (badge.length) {
                        badge.text((parseInt(badge.text(), 10) || 0) + 1);
                    }
                    updateStat(statCards, 1);
                    form.trigger('reset');
                    const highest = Math.max(...cardList.children('[data-card-id]').map(function() {
                        return parseInt($(this).data('order'), 10) || 0;
                    }).get(), 0) + 1;
                    form.find('input[name="order_index"]').val(highest);
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
            const contentWrap = btn.closest('[data-content-id]');
            const cardList = contentWrap.find('[data-card-list]');
            const proceed = () => {
                $.ajax({
                    url,
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: () => {
                    cardWrap.remove();
                    const badge = contentWrap.find('[data-card-count]');
                        if (badge.length) {
                            badge.text(Math.max(0, (parseInt(badge.text(), 10) || 0) - 1));
                        }
                        if (!cardList.children('[data-card-id]').length) {
                            cardList.append('<p class="m-0 text-sm opacity-75" data-empty-card>Belum ada card untuk content ini.</p>');
                        } else {
                            relabelCards(cardList);
                        }
                        updateStat(statCards, -1);
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

        $(document).on('click', '[data-toggle-card-edit]', function() {
            const target = document.getElementById(this.dataset.target);
            if (!target) return;
            target.classList.toggle('hidden');
            if (!target.classList.contains('hidden')) {
                target.querySelector('input[name="order_index"]')?.focus();
            }
        });

        $(document).on('submit', '[data-card-edit]', function(e) {
            e.preventDefault();
            const form = $(this);
            const cardWrap = form.closest('[data-card-id]');
            const cardList = cardWrap.closest('[data-card-list]');
            const oldOrder = parseInt(cardWrap.data('order'), 10) || 0;
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                success: (res) => {
                    const newOrder = res.card?.order_index ?? parseInt(form.find('input[name="order_index"]').val(), 10) || 1;
                    rebalanceCardOrders(cardList, cardWrap, oldOrder, newOrder);
                    form.addClass('hidden');
                    toast(res.message || 'Card diperbarui');
                },
                error: (xhr) => {
                    const msg = xhr.responseJSON?.message || 'Gagal memperbarui card';
                    toast(msg, false);
                }
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        if (window.jQuery) {
            initLessonPage(window.jQuery);
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        script.integrity = 'sha256-/xUj+3OJ+Y3VSW3UUAeT6VZf4p7uapt3/3p9m4y74wE=';
        script.crossOrigin = 'anonymous';
        script.onload = () => initLessonPage(window.jQuery);
        document.head.appendChild(script);
    });
</script>
@endsection
