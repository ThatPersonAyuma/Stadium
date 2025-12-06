@extends('layouts.dashboard')
@section('title', 'Lesson')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-10 md:pt-8 lg:px-16 xl:px-20 text-white overflow-hidden font-[var(--font-body)]">
    {{-- Background --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(120%_80%_at_18%_10%,rgba(0,64,168,0.25),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_82%_0%,rgba(0,46,135,0.35),transparent_60%)]"></div>

    <div class="relative space-y-6">
        <x-dashboard-header title="Course" :show-plant="false" />

        {{-- Progress --}}
        <div class="bg-white/10 border border-white/15 rounded-2xl p-4 md:p-5 shadow-xl backdrop-blur-sm">
            <div class="flex items-center justify-between text-sm font-semibold mb-2">
                <span>Progress</span>
                <span>{{ $progress }}%</span>
            </div>
            <div class="h-4 w-full rounded-full bg-white/20 overflow-hidden shadow-inner">
                <div class="h-full bg-gradient-to-r from-amber-300 to-orange-500" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        {{-- Card Player Container --}}
        <div class="relative overflow-hidden rounded-3xl bg-[#0a3a9a] border border-white/15 shadow-2xl p-6 md:p-8 space-y-6">
            <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-blue-900/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-indigo-900/35 blur-3xl"></div>

            {{-- Title --}}
            <div>
                <p class="text-xs md:text-sm uppercase tracking-[0.22em] font-[var(--font-heading)] font-extrabold text-white/70">
                    Modul {{ $lesson->order_index ?? '-' }} · {{ $activeCard?->content->title ?? 'Card' }}
                </p>
                <h2 class="text-3xl md:text-4xl font-[var(--font-heading)] font-black leading-tight">{{ $lesson->title }}</h2>
                <p class="text-sm opacity-80 m-0">{{ $lesson->description }}</p>
            </div>

            {{-- Block progress --}}
            <div class="space-y-3">
                <div class="h-3 w-full rounded-full bg-white/10 overflow-hidden shadow-inner">
                    <div id="block-progress-bar" class="h-full bg-gradient-to-r from-amber-300 to-orange-500" style="width: 0%"></div>
                </div>
                <p id="block-status" class="text-sm font-semibold text-white/80 m-0">Memuat...</p>
            </div>

            {{-- Card Player --}}
            <div id="block-player"
                 data-cards='@json($cardsPayload)'
                 data-detail="{{ route('course.detail', $courseId) }}"
                 class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white/5 via-white/0 to-indigo-900/20 border border-white/10 min-h-[320px]">
                <div id="block-display" class="p-2 md:p-3"></div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('course.detail', $courseId) }}"
                   class="text-xs sm:text-sm font-semibold text-amber-200 hover:text-white transition">
                    Kembali ke detail
                </a>

                <div class="flex items-center gap-3">
                    <button id="btn-prev"
                        class="px-6 py-3 rounded-2xl bg-white/10 border border-white/20 text-base font-semibold uppercase text-white/80 hover:text-white hover:bg-white/15 disabled:opacity-40 disabled:cursor-not-allowed transition">
                        Sebelumnya
                    </button>

                    <button id="btn-next"
                        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-300 to-orange-400 text-base font-black uppercase text-white shadow-2xl hover:from-amber-200 hover:to-orange-300 transition">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const resourceUrlPrefix = "{{ asset(App\Helpers\FileHelper::getBlockUrlPath($courseId, $lesson->id, $contentId)) }}";

document.addEventListener("DOMContentLoaded", () => {
    const FINISH_URL = "{{ route('finish-content') }}";
    const content_id = {{ $contentId }};
    const el = id => document.getElementById(id);

    const container = el("block-player");
    const cards = JSON.parse(container.dataset.cards || "[]");
    const detailUrl = container.dataset.detail;

    const display = el("block-display");
    const statusEl = el("block-status");
    const progressBar = el("block-progress-bar");
    const btnPrev = el("btn-prev");
    const btnNext = el("btn-next");

    let cardIndex = 0;

    // -----------------------------------------------
    // STATE CACHE (agar back tidak fetch ulang)
    // -----------------------------------------------
    const cardStates = cards.map(() => ({
        quizAnswered: 0,
        quizTotal: 0,
        quizData: {}, // blockId -> { selected, correct, explanationShown }
        fullyCompleted: false
    }));

    // -----------------------------------------------
    // Utilities
    // -----------------------------------------------
    const escapeHtml = val => val?.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;") || "";

    const getAssetUrl = (block, data) => {
        if (data.asset_url) return data.asset_url;
        if (data.url) return data.url;
        if (data.filename && block.card_id) {
            return `${resourceUrlPrefix}/${block.card_id}-card/${block.id}-${data.filename}`;
        }
        return "";
    };

    // -----------------------------------------------
    // Renderers
    // -----------------------------------------------
    const renderers = {
        text: ({ data }) => `
            <div class="rounded-2xl bg-white/10 border border-white/15 p-5 text-white text-lg whitespace-pre-line">
                ${escapeHtml(data.content)}
            </div>
        `,
        code: ({ data }) => `
            <div class="rounded-2xl bg-slate-900/60 border border-white/15 p-5">
                <pre class="text-sm font-mono text-emerald-100 whitespace-pre-wrap m-0">
${escapeHtml(data.code)}
                </pre>
            </div>
        `,
        image: ({ block, data }) => `
            <div class="flex flex-col items-center gap-3">
                <img src="${escapeHtml(getAssetUrl(block, data))}" class="w-full max-h-[360px] object-contain rounded-xl border border-white/10">
                ${data.alt ? `<p class="text-sm text-white/70">${escapeHtml(data.alt)}</p>` : ""}
            </div>
        `,
        gif: ({ block, data }) => renderers.image({ block, data }),

        video: ({ block, data }) => `
            <video src="${escapeHtml(getAssetUrl(block, data))}" controls class="w-full max-h-[360px] rounded-xl border border-white/10"></video>
        `,

        // === QUIZ ===
        quiz: ({ block, data }) => {
            const blockId = block.id;
            const choices = data.choices || {};
            const question = escapeHtml(data.question);

            const buttons = Object.entries(choices)
                .map(([key, val]) => `
                    <button 
                        data-key="${escapeHtml(key)}"
                        class="quiz-option w-full rounded-2xl bg-white/10 text-white font-semibold text-lg px-5 py-4 shadow-lg hover:bg-white/20 transition"
                    >
                        ${escapeHtml(val)}
                    </button>
                `).join("");

            return `
                <div class="quiz-block" data-block="${blockId}">
                    <div class="text-xl font-bold text-white mb-3">${question}</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 quiz-choices">${buttons}</div>

                    <div class="quiz-explanation hidden mt-4 p-3 rounded-2xl bg-white/10 border border-white/20 text-sm text-white/90">
                        <p class="font-semibold mb-1">Penjelasan:</p>
                        <p>${escapeHtml(data.explanation || "")}</p>
                    </div>
                </div>
            `;
        },

        default: ({ block }) => `
            <div class="p-6 text-center text-white/80">
                Unknown block type "${escapeHtml(block.type)}".
            </div>
        `
    };

    // -----------------------------------------------
    // Attach QUIZ EVENTS (with state caching)
    // -----------------------------------------------
    function attachQuizEvents(block, cardState) {
        const blockId = block.dataset.block;
        const buttons = block.querySelectorAll(".quiz-option");
        const explanationEl = block.querySelector(".quiz-explanation");

        // ========== Jika sudah pernah dikerjakan (cache restore) ==========
        if (cardState.quizData[blockId]) {
            const saved = cardState.quizData[blockId];

            buttons.forEach(btn => {
                const key = btn.dataset.key;
                btn.disabled = true;

                if (key === saved.correct) {
                    btn.classList.add("!bg-green-600/70");
                }
                if (key === saved.selected && saved.selected !== saved.correct) {
                    btn.classList.add("!bg-red-600/70");
                }
            });

            if (saved.explanationShown) explanationEl.classList.remove("hidden");

            return; // skip attach event
        }

        // ========== Jika belum pernah dikerjakan ==========
        buttons.forEach(btn => {
            btn.addEventListener("click", async () => {
                const selected = btn.dataset.key;

                buttons.forEach(b => b.disabled = true);

                // KIRIM KE SERVER — hanya sekali
                const res = await fetch("{{ route('lesson-answer') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ block_id: blockId, answer: selected, content_id })
                });

                const data = await res.json();
                if (data.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: data.title,
                        text: data.message,
                    }).then(() => {
                        if (data.redirect) window.location.href = data.redirect;
                    });
                }
                // Highlight
                buttons.forEach(b => {
                    const key = b.dataset.key;
                    if (key === data.correct_answer) b.classList.add("!bg-green-600/70");
                    if (key === selected && selected !== data.correct_answer)
                    {
                        b.classList.add("!bg-red-600/70");
                        loseHeart();
                    }
                });

                explanationEl.classList.remove("hidden");

                // ====== SIMPAN STATE ======
                cardState.quizData[blockId] = {
                    selected,
                    correct: data.correct_answer,
                    explanationShown: true
                };
                cardState.quizAnswered++;

                updateNextButtonLock(cardState);
            });
        });
    }

    // -----------------------------------------------
    // LOCK NEXT BUTTON until all quiz are answered
    // -----------------------------------------------
    function updateNextButtonLock(state) {
        if (state.quizTotal > 0 && state.quizAnswered < state.quizTotal) {
            btnNext.disabled = true;
            btnNext.classList.add("opacity-40", "cursor-not-allowed");
        } else {
            btnNext.disabled = false;
            btnNext.classList.remove("opacity-40", "cursor-not-allowed");
        }
    }

    // -----------------------------------------------
    // Render CARD
    // -----------------------------------------------
    function renderCard() {
        const card = cards[cardIndex];
        const cardState = cardStates[cardIndex];

        // Update progress bar
        progressBar.style.width = `${Math.round((cardIndex + 1) * 100 / cards.length)}%`;
        statusEl.textContent = `Card ${cardIndex + 1} dari ${cards.length}`;

        // Render UI
        display.innerHTML = `
            <div class="flex flex-col gap-6">
                ${card.blocks.map(b => (renderers[b.type] || renderers.default)({ block: b, data: b.data })).join("")}
            </div>
        `;

        // Count total quizzes
        cardState.quizTotal = card.blocks.filter(b => b.type === "quiz").length;

        // Re-bind quiz events
        const quizBlocks = display.querySelectorAll(".quiz-block");
        quizBlocks.forEach(qb => attachQuizEvents(qb, cardState));

        // Setup buttons
        btnPrev.disabled = cardIndex === 0;
        btnNext.textContent = (cardIndex === cards.length - 1) ? "Selesai" : "Selanjutnya";

        updateNextButtonLock(cardState);

        btnNext.onclick = async () => {
            if (cardIndex === cards.length - 1) {
                await finishContent();
                return;
            }
            cardIndex++;
            renderCard();
        };
    }

    // -----------------------------------------------
    // Finish content
    // -----------------------------------------------
    async function finishContent() {
        const res = await fetch(FINISH_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ content_id })
        });

        const data = await res.json();

        if (data.status === "ok") {
            window.location.href = data.redirect ?? window.location.href;
        }
    }

    // Prev
    btnPrev.onclick = () => {
        if (cardIndex > 0) {
            cardIndex--;
            renderCard();
        }
    };

    renderCard();
    function loseHeart() {
        const hpEl = document.querySelector(".hp-number");
        if (!hpEl) return;

        let hp = parseInt(hpEl.textContent);
        if (isNaN(hp)) hp = 0;

        if (hp > 0) {
            hp--;               // Kurangi 1
            hpEl.textContent = hp;
        }
    }
});
</script>

@endsection 