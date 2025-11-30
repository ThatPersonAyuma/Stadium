@extends('layouts.dashboard')
@section('title', 'Live Quiz')

@section('styles')
    @vite(['resources/js/echo.js'])
@endsection

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-3xl space-y-10">

        {{-- Header --}}
        <x-dashboard-header title="Live Quiz"/>

        {{-- ============================
            STATE: WAITING ROOM
        ============================ --}}
        <div id="waiting-section" class="state-block bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg text-center space-y-4">
            <h3 class="text-2xl font-bold">Menunggu Quiz Dimulai...</h3>
            <p class="text-white/80">Teacher akan memulai quiz dalam beberapa saat.</p>

            <div class="animate-spin h-10 w-10 mx-auto mt-4 border-4 border-white/30 border-t-white rounded-full"></div>
            <div id="join-info" class="text-white/70 text-sm"></div>
        </div>
        {{-- ============================
            STATE: WAITING ANSWER REVIEW
        ============================ --}}
        <div id="waitinganswer-section" class="state-block bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg text-center space-y-4 hidden">
            <h3 class="text-2xl font-bold">Jawaban Terkirim!</h3>
            <p class="text-white/80">Menunggu teacher mengoreksi jawaban...</p>

            <div class="animate-spin h-10 w-10 mx-auto mt-4 border-4 border-white/30 border-t-white rounded-full"></div>

            <div class="text-white/60 text-sm mt-3">
                Harap tunggu hasil penilaian.
            </div>
        </div>
        {{-- ============================
            STATE: QUESTION
        ============================ --}}
        <div id="question-section" class="state-block hidden bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg">
            <h3 id="questionText" class="text-xl font-bold mb-4"></h3>
            <div id="choiceContainer" class="flex flex-col gap-3"></div>
        </div>

        {{-- ============================
            STATE: RESULT
        ============================ --}}
        <div id="result-section" class="state-block hidden bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg text-center">
            <h3 id="result-title" class="text-2xl font-bold mb-4"></h3>
            <p id="result-desc" class="text-white/80"></p>
        </div>

        {{-- ============================
            STATE: LEADERBOARD
        ============================ --}}
        <div id="leaderboard-section" class="state-block hidden bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg">
            <h3 class="text-2xl font-bold mb-6">Leaderboard</h3>
            <div id="leaderboardList" class="space-y-3"></div>
        </div>

        {{-- ======================================================
            STATE 5 — END QUIZ
        ====================================================== --}}
        <div id="end-section"
            class="hidden bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg min-h-[60vh] text-center">

            <h3 class="text-3xl font-bold mb-4">🎉 Quiz Telah Berakhir</h3>
            <p class="text-white/80 mb-6">Berikut hasil akhir seluruh peserta:</p>

            <div id="endLeaderboard" class="space-y-3 mb-6">
                <p class="text-white/70">Mengambil data scoreboard...</p>
            </div>

            <a href="{{ route('dashboard.student') }}" 
            class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-3 rounded-lg transition">
            Kembali
            </a>
        </div>

    </div>
</div>


{{-- ============================
     JAVASCRIPT SECTION
============================ --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // ELEMENTS
    const el = {
        waiting: document.getElementById('waiting-section'),
        waitinganswer: document.getElementById('waitinganswer-section'),
        question: document.getElementById('question-section'),
        result: document.getElementById('result-section'),
        leaderboard: document.getElementById('leaderboard-section'),
        end: document.getElementById('end-section'),

        questionText: document.getElementById('questionText'),
        choiceContainer: document.getElementById('choiceContainer'),
        resultTitle: document.getElementById('result-title'),
        resultDesc: document.getElementById('result-desc'),
        leaderboardList: document.getElementById('leaderboardList'),
        endLeaderboard: document.getElementById('endLeaderboard'),
    };

    let quizId = {{ $quiz_id }};
    let studentId = @json(auth()->user()->student->id);
    let myAnswer = "";

    // STATE MANAGER
    function toggle(state) {
        document.querySelectorAll(".state-block").forEach(el => el.classList.add("hidden"));
        if (el[state]) el[state].classList.remove("hidden");
    }

    const states = {
        waiting: () => toggle('waiting'),
        question: () => toggle('question'),
        waitingAnswer: () => toggle('waitinganswer'),
        result: () => toggle('result'),
        leaderboard: () => toggle('leaderboard'),
        endQuiz: () => toggle('end'),
    };

    // WEBSOCKET EVENTS
    window.Echo.private('quiz.' + quizId)

        .listen('.quiz.start', (e) => {
            states.question();
            renderQuestion(e);
        })

        .listen('.quiz.question.sent', (e) => {
            states.question();
            renderQuestion(e);
        })

        .listen('.quiz.review', (e) => {
            states.result();
            console.log(e);

            const a = e.answer;

            let isCorrect = (`${a.label}. ${a.text}` == myAnswer);
            el.resultTitle.innerText = "Jawaban yang Benar:";
            let text = `
                <div class="text-left space-y-2">
                    <p class="text-white/90 font-semibold">${a.question}</p>`;
            text += `<p class="${isCorrect ? 'text-green-400' : 'text-red-400'} font-bold">
                    Jawabanmu ${isCorrect ? 'benar' : 'salah'}: ${myAnswer}
                </p>
                    <p class="text-green-400 font-bold text-lg">✔ ${a.label}. ${a.text}</p>
                </div>`;
            el.resultDesc.innerHTML = text;
        })
        .listen('.quiz.scoreboard', (e) => {
            states.leaderboard();
            console.log(e);
            renderLeaderboard(e.scoreboard);
        })

        .listen('.quiz.ended', (e) => {
            states.endQuiz();
            renderEndLeaderboard(e.scoreboard);
        });


    // RENDER QUESTION
    function renderQuestion(q) {
        console.log(q)
        el.questionText.innerText = q.question ?? "Pertanyaan tidak ditemukan.";
        el.choiceContainer.innerHTML = "";

        q.options.forEach(option => {
            const btn = document.createElement('button');
            btn.className = "rounded-lg bg-white/10 px-4 py-3 text-left shadow hover:bg-white/20 transition";
            btn.innerText = `${option.label}. ${option.text}`;
            myAnswer = 'Kamu tidak menjawab';
            btn.onclick = () => {
                sendAnswer(option.id, `${option.label}. ${option.text}`);
                states.waitingAnswer();
            };

            el.choiceContainer.appendChild(btn);
        });
    }

    // SEND ANSWER
    async function sendAnswer(id, answer) {
        myAnswer = answer;
        let res = await fetch("{{ route('quiz.answer') }}", {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                student_id: studentId,
                answer_id: id,
                quiz_id: quizId,
            })
        });
        let data = await res.json();
        console.log(data);
    }

    // LIVE LEADERBOARD
    function renderLeaderboard(items) {
        el.leaderboardList.innerHTML = '';
        items.forEach((p, index) => {
            el.leaderboardList.innerHTML += `
                <div class="flex justify-between bg-white/5 rounded-xl px-4 py-3">
                    <span class="font-bold">${index + 1}. ${p.username}</span>
                    <span class="font-black">${p.score} pts</span>
                </div>
            `;
        });
    }

    // END QUIZ LEADERBOARD
    function renderEndLeaderboard(items) {
        el.endLeaderboard.innerHTML = '';

        if (!items || items.length === 0) {
            el.endLeaderboard.innerHTML = '<p class="text-white/70">Belum ada data.</p>';
            return;
        }

        items.forEach((p, i) => {
            el.endLeaderboard.innerHTML += `
                <div class="flex justify-between bg-white/5 rounded-xl px-4 py-3">
                    <span class="font-bold">${i + 1}. ${p.username}</span>
                    <span class="font-black">${p.score} pts</span>
                </div>
            `;
        });
    }

});
</script>
@endsection

