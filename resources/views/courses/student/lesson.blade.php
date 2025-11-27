@extends('layouts.dashboard')
@section('title', 'Lesson')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-10 md:pt-8 lg:px-16 xl:px-20 text-white overflow-hidden font-[var(--font-body)]">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(120%_80%_at_18%_10%,rgba(0,64,168,0.25),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_82%_0%,rgba(0,46,135,0.35),transparent_60%)]"></div>

    <div class="relative space-y-6">
        <x-dashboard-header title="Course" :show-plant="false" />

        <div class="bg-white/10 border border-white/15 rounded-2xl p-4 md:p-5 shadow-xl backdrop-blur-sm">
            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                <span>Progress</span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="h-4 w-full rounded-full bg-white/20 overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-amber-300 to-orange-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl bg-[#0a3a9a] border border-white/15 shadow-2xl p-6 md:p-8 space-y-6">
            <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-blue-900/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-indigo-900/35 blur-3xl"></div>

            <div class="relative space-y-4">
                <div>
                    <p class="text-xs md:text-sm uppercase tracking-[0.22em] font-[var(--font-heading)] font-extrabold text-white/70">
                        Modul {{ $lesson->order_index ?? '-' }} · {{ $activeCard?->content->title ?? 'Card' }}
                    </p>
                    <h2 class="text-3xl md:text-4xl font-[var(--font-heading)] font-black leading-tight drop-shadow-sm">
                        {{ $lesson->title }}
                    </h2>
                    <p class="text-sm opacity-80 m-0 font-[var(--font-body)]">{{ $lesson->description }}</p>
                </div>

                <div class="space-y-3">
                    <div class="h-3 w-full rounded-full bg-white/10 overflow-hidden shadow-inner">
                        <div id="block-progress-bar" class="h-full bg-gradient-to-r from-amber-300 to-orange-500" style="width: 0%"></div>
                    </div>
                    <p id="block-status" class="text-sm font-semibold text-white/80 m-0">Memuat...</p>
                </div>

                <div id="block-player"
                     data-blocks='@json($blocksPayload)'
                     data-detail-url="{{ route('course.detail', $courseId) }}"
                     data-card-title="{{ $activeCard?->content->title ?? 'Card' }}"
                     class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white/5 via-white/0 to-indigo-900/20 border border-white/10 min-h-[320px] flex font-[var(--font-body)]">
                    <div id="block-display" class="relative w-full"></div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('course.detail', $courseId) }}" class="text-xs sm:text-sm font-[var(--font-body)] font-semibold text-amber-200 hover:text-white transition">Kembali ke detail</a>
                    <div class="flex flex-wrap items-center gap-3 justify-end">
                        <button id="btn-prev" type="button" class="px-6 py-3 rounded-2xl bg-white/10 border border-white/20 text-base font-[var(--font-heading)] font-semibold tracking-wide uppercase text-white/80 hover:text-white hover:bg-white/15 disabled:opacity-40 disabled:cursor-not-allowed transition">Sebelumnya</button>
                        <button id="btn-next" type="button" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-300 to-orange-400 text-base font-[var(--font-heading)] font-black tracking-wider uppercase text-white shadow-2xl shadow-amber-500/50 hover:from-amber-200 hover:to-orange-300 transition">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const player = document.querySelector('#block-player');
    if (!player) return;

    const blocks = JSON.parse(player.dataset.blocks || '[]');
    const detailUrl = player.dataset.detailUrl || '{{ route('course.detail', $courseId) }}';
    const display = document.querySelector('#block-display');
    const progressBar = document.querySelector('#block-progress-bar');
    const statusEl = document.querySelector('#block-status');
    const btnPrev = document.querySelector('#btn-prev');
    const btnNext = document.querySelector('#btn-next');

    let idx = 0;
    let requireAnswer = false;

    const escapeHtml = (value) => {
        if (value === undefined || value === null) return '';
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const renderQuiz = (block) => {
        const data = block.data || {};
        const choices = data.choices || {};
        const entries = Object.entries(choices);
        const question = escapeHtml(data.question || 'Pertanyaan tidak tersedia');
        const explanation = escapeHtml(data.explanation || '');
        const correct = data.answer || '';

        const optionBtns = entries.map(([key, val]) => {
            return `<button data-key="${escapeHtml(key)}" class="quiz-option w-full rounded-2xl bg-white/10 text-white font-[var(--font-heading)] font-semibold text-lg md:text-xl px-5 py-4 shadow-lg shadow-amber-400/40 hover:bg-white/20 transition duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-white/80">${escapeHtml(val)}</button>`;
        }).join('');

        display.innerHTML = `
            <div class="w-full font-[var(--font-body)]">
                <div class="text-xl md:text-2xl font-[var(--font-heading)] font-black leading-tight text-white mb-4">${question}</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    ${optionBtns}
                </div>
                <div id="quiz-explanation" class="hidden mt-4 rounded-2xl bg-white/10 border border-white/20 p-3 text-sm text-white/90">
                    ${explanation ? `<p class="font-semibold mb-1">Penjelasan:</p><p class="m-0">${explanation}</p>` : '<p class="m-0">Jawaban tersimpan.</p>'}
                </div>
            </div>
        `;

        requireAnswer = true;
        btnNext.disabled = true;

        const buttons = display.querySelectorAll('.quiz-option');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const choice = btn.dataset.key;
                buttons.forEach(b => b.classList.remove('ring', 'ring-4', 'ring-red-400', 'ring-lime-400'));

                if (choice === correct) {
                    btn.classList.add('ring', 'ring-4', 'ring-lime-400');
                } else {
                    btn.classList.add('ring', 'ring-4', 'ring-red-400');
                    const correctBtn = Array.from(buttons).find(b => b.dataset.key === correct);
                    if (correctBtn) correctBtn.classList.add('ring', 'ring-4', 'ring-lime-400');
                }

                const exp = document.querySelector('#quiz-explanation');
                if (exp) exp.classList.remove('hidden');

                requireAnswer = false;
                btnNext.disabled = false;
            });
        });
    };

    const renderBlock = () => {
        if (!blocks.length) {
            statusEl.textContent = 'Belum ada block';
            progressBar.style.width = '0%';
            display.innerHTML = '<div class="p-6 text-center text-white/70">Belum ada block pada card ini.</div>';
            btnPrev.disabled = true;
            btnNext.textContent = 'Kembali';
            btnNext.onclick = () => window.location.href = detailUrl;
            return;
        }

        const block = blocks[idx];
        const percent = Math.round(((idx + 1) / blocks.length) * 100);
        progressBar.style.width = `${percent}%`;
        statusEl.textContent = `Step ${idx + 1} dari ${blocks.length}`;
        btnPrev.disabled = idx === 0;
        btnNext.textContent = idx === blocks.length - 1 ? 'Selesai' : 'Selanjutnya';
        btnNext.disabled = false;
        requireAnswer = false;

        const type = block.type;
        const data = block.data || {};

        if (type === 'text') {
            display.innerHTML = `
                <div class="rounded-2xl bg-white/10 border border-white/15 p-5 shadow-lg text-white text-lg font-[var(--font-body)] leading-relaxed whitespace-pre-line">
                    ${escapeHtml(data.content || 'Tidak ada teks')}
                </div>
            `;
        } else if (type === 'code') {
            display.innerHTML = `
                <div class="rounded-2xl bg-slate-900/60 border border-white/15 p-5 shadow-lg">
                    <pre class="text-sm md:text-base font-mono text-emerald-100 whitespace-pre-wrap m-0">${escapeHtml(data.code || data.content || 'Tidak ada kode')}</pre>
                </div>
            `;
        } else if (type === 'image' || type === 'gif') {
            const url = block.asset_url || data.path || data.url || (data.filename ? ('/storage/' + data.filename) : '');
            display.innerHTML = `
                <div class="flex flex-col items-center gap-3 w-full">
                    <div class="w-full rounded-2xl overflow-hidden bg-white/5 border border-white/15 shadow-inner">
                        <img src="${escapeHtml(url)}" alt="${escapeHtml(data.alt || '')}" class="w-full h-full object-contain max-h-[360px] bg-slate-900/30">
                    </div>
                    ${data.alt ? `<p class="text-sm text-white/80 m-0">${escapeHtml(data.alt)}</p>` : ''}
                </div>
            `;
        } else if (type === 'quiz') {
            renderQuiz(block);
        } else if (type === 'video') {
            const url = block.asset_url || data.url || (data.filename ? ('/storage/' + data.filename) : '');
            display.innerHTML = `
                <div class="w-full rounded-2xl overflow-hidden bg-black border border-white/15 shadow-inner">
                    <video src="${escapeHtml(url)}" controls class="w-full max-h-[380px]"></video>
                </div>
            `;
        } else {
            display.innerHTML = `
                <div class="p-6 text-center text-white/80">
                    Tipe block ${escapeHtml(type || 'tidak diketahui')} belum didukung.
                </div>
            `;
        }
    };

    btnPrev?.addEventListener('click', () => {
        if (idx > 0) {
            idx -= 1;
            renderBlock();
        }
    });

    btnNext?.addEventListener('click', () => {
        if (requireAnswer) return;
        if (idx < blocks.length - 1) {
            idx += 1;
            renderBlock();
        } else {
            window.location.href = detailUrl;
        }
    });

    renderBlock();
});
</script>
@endsection
