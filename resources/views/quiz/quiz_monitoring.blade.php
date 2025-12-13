@extends('layouts.dashboard')
@section('title', 'Teacher Live Quiz')

@section('styles')
    @vite(['resources/js/echo.js'])
@endsection

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-4xl space-y-10">

        {{-- Header --}}
        <x-dashboard-header title="Teacher Control Panel"/>


        {{-- ======================================================
             STATE 1 — OPEN QUIZ
        ======================================================= --}}
        <div id="open-section"
            class="bg-white/10 rounded-2xl p-8 shadow-lg text-center space-y-6">

            <h3 class="text-xl font-bold">Open Quiz for {{$quiz->title}}</h3>
            <p class="text-white/70">Klik tombol di bawah untuk membuka quiz.</p>

            <button id="openQuizBtn"
                class="bg-green-600 px-6 py-3 rounded-lg font-bold text-white hover:bg-green-500 transition">
                Open Quiz
            </button>
        </div>


        {{-- ======================================================
             STATE 2 — WAITING LOBBY (DISPLAY CODE + PARTICIPANTS)
        ======================================================= --}}
        <div id="lobby-section"
            class="hidden bg-white/10 rounded-2xl p-8 shadow-lg space-y-6">

            <h3 class="text-xl font-bold text-center">Kode Quiz</h3>
            <div id="quizCodeDisplay"
                 class="text-4xl font-extrabold text-center tracking-widest">
                 ------
            </div>

            <h3 class="text-lg font-bold mt-6">Peserta Bergabung</h3>

            <div id="participantList" class="space-y-2 bg-white/5 p-4 rounded-xl">
                <p class="text-white/60">Menunggu peserta...</p>
            </div>

            <button id="goToStartBtn"
                class="w-full bg-blue-600 px-4 py-3 rounded-lg font-bold text-white hover:bg-blue-500 transition mt-4">
                Lanjut Ke Pengaturan Quiz
            </button>
        </div>


        {{-- ======================================================
             STATE 3 — CONTROL PANEL BEFORE QUIZ STARTED
        ======================================================= --}}
        <div id="control-section"
             class="hidden bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg space-y-6">

            <h3 class="text-xl font-bold">Pengaturan Quiz</h3>

            {{-- Interval Input --}}
            <div>
                <label class="block mb-2 text-white/80">Interval antar soal (detik)</label>
                <input type="number" id="intervalInput" min="3" value="10"
                       class="rounded-lg border border-white/30 bg-white/10 px-4 py-3 text-white placeholder-white/70 w-full focus:ring-2 focus:ring-blue-400">
            </div>

            <button id="startQuizBtn"
                class="w-full bg-green-600 px-4 py-3 rounded-lg font-bold text-white shadow-lg hover:bg-green-500 transition">
                Mulai Quiz
            </button>
        </div>


        {{-- ======================================================
             STATE 4 — LIVE LEADERBOARD (RUNNING QUIZ)
        ======================================================= --}}
        <div id="leaderboard-section"
             class="hidden bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg min-h-[60vh]">

            <h3 class="text-2xl font-bold mb-6">Live Leaderboard</h3>
            <div id="leaderboardList" class="space-y-3">
                <p class="text-white/70">Menunggu peserta menjawab...</p>
            </div>
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

            <a href="{{ route('quiz.index') }}" 
            class="inline-block bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-3 rounded-lg transition">
            Kembali
            </a>
        </div>


    </div>
</div>



{{-- ===========================================================
     JAVASCRIPT — Teacher Logic + WebSocket
=========================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ================================
    // DOM ELEMENTS
    // ================================
    const openSection = document.getElementById('open-section');
    const lobbySection = document.getElementById('lobby-section');
    const controlSection = document.getElementById('control-section');
    const leaderboardSection = document.getElementById('leaderboard-section');
    const endSection = document.getElementById('end-section');

    const openQuizBtn = document.getElementById('openQuizBtn');
    const goToStartBtn = document.getElementById('goToStartBtn');
    const startQuizBtn = document.getElementById('startQuizBtn');

    const participantList = document.getElementById('participantList');
    const leaderboardList = document.getElementById('leaderboardList');
    const quizCodeDisplay = document.getElementById('quizCodeDisplay');
    const intervalInput = document.getElementById('intervalInput');

    let quizId = {{ $quiz->id }};
    let totalQuestions = {{ $quiz->questions->count() }};
    let participants = []; // List

    // ================================
    // SWITCH UI STATES
    // ================================
    function switchState(state) {
        openSection.classList.add("hidden");
        lobbySection.classList.add("hidden");
        controlSection.classList.add("hidden");
        leaderboardSection.classList.add("hidden");
        endSection.classList.add("hidden");

        if (state === 'open') openSection.classList.remove('hidden');
        if (state === 'lobby') lobbySection.classList.remove('hidden');
        if (state === 'control') controlSection.classList.remove('hidden');
        if (state === 'leaderboard') leaderboardSection.classList.remove('hidden');
        if (state === 'end') endSection.classList.remove('hidden');
    }


    // ======================================================
    // STATE 1: OPEN QUIZ
    // ======================================================
    openQuizBtn.onclick = async () => {
        let res = await fetch("{{ route('quiz.open', $quiz->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ quiz_id: quizId })
        });

        let data = await res.json();
        console.log(data);
        quizCodeDisplay.innerText = data.code;
        renderParticipants(data.participants);
        switchState('lobby');
    };


    // ======================================================
    // STATE 2: LOBBY — PARTICIPANTS JOINING
    // ======================================================
    window.Echo.private('quiz.' + quizId)
        .listen('.quiz.participant.registered', (e) => {
            renderParticipant(e.participants);
        })

    function renderParticipants(list) {
        participantList.innerHTML = '';

        if (list.length === 0) {
            participantList.innerHTML = '<p class="text-white/60">Belum ada peserta.</p>';
            return;
        }

        list.forEach((p, i) => {
            participantList.innerHTML += `
                <div class="bg-white/5 px-4 py-3 rounded-xl">
                    ${i + 1}. ${p.username}
                </div>
            `;
        });
    }
    goToStartBtn.onclick = () => {
        switchState('control');
    };
    async function questionTimeOut(){
        let res = await fetch("{{ route('quiz.end-question') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ quiz_id: quizId })
        });

        let data = await res.json();
        console.log(data);
    }
    async function scoreboard(){
        let res = await fetch("{{ route('quiz.scoreboard') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ quiz_id: quizId })
        });

        let data = await res.json();
        console.log(data);
        
    }
    // lanjut ke panel interval
    startQuizBtn.onclick = async () => {
        let interval = parseInt(intervalInput.value);
        let delay = 10; // tambahan 10 detik
        fetch("{{ route('quiz.start', $quiz->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ quiz_id:quizId ,interval: interval })
        }).then(res => res.json())
        .then(res => {renderLeaderboard(res.scoreboard)});

        console.log("cek")
        switchState('leaderboard');
        let currentIndex = 1;

        function sendNextQuestion() {

            if (currentIndex > totalQuestions) {
                console.log("All questions sent.");
                sendEnded();
                return;
            }

            fetch("{{ route('quiz.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    quiz_id: quizId,
                    quiz_order : currentIndex
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.done) {
                    console.log("Quiz finished.");
                    return;
                }
                console.log("Sent question", currentIndex + 1);

                currentIndex++;
                setTimeout(questionTimeOut, interval * 1000);
                setTimeout(scoreboard, (interval + (delay/2)) * 1000);
                // jadwalkan pertanyaan berikutnya
                setTimeout(sendNextQuestion, (interval+delay) * 1000);
            });
        }

        // mulai loop pertama
        sendNextQuestion();
    };


    // ======================================================
    // STATE 4: LIVE LEADERBOARD (REALTIME)
    // ======================================================
    window.Echo.private('quiz.' + quizId)
        .listen('.quiz.answer.submitted', (e) => {
            renderLeaderboard(e.scoreboard);
        })
        .listen('.quiz.ended', (e) => {
            switchState('end'); // sembunyikan section lain, tampilkan end-section
            renderEndLeaderboard(e.scoreboard);
        });
    
    function renderLeaderboard(items) {
        leaderboardList.innerHTML = '';

        if (items.length === 0) {
            leaderboardList.innerHTML = '<p class="text-white/70">Belum ada data.</p>';
            return;
        }

        items.forEach((p, index) => {
            leaderboardList.innerHTML += `
                <div class="flex justify-between bg-white/5 rounded-xl px-4 py-3">
                    <span class="font-bold">${index + 1}. ${p.username}</span>
                    <span class="font-black">${p.score} pts</span>
                </div>
            `;
        });
    }

    function renderEndLeaderboard(items) {
        const endLeaderboard = document.getElementById('endLeaderboard');
        endLeaderboard.innerHTML = '';

        if (!items || items.length === 0) {
            endLeaderboard.innerHTML = '<p class="text-white/70">Belum ada data.</p>';
            return;
        }

        // sort descending
        // items.sort((a, b) => b.score - a.score);

        items.forEach((p, i) => {
            endLeaderboard.innerHTML += `
                <div class="flex justify-between bg-white/5 rounded-xl px-4 py-3">
                    <span class="font-bold">${i + 1}. ${p.username}</span>
                    <span class="font-black">${p.score} pts</span>
                </div>
            `;
        });
    }
    function sendEnded(){
        fetch("{{ route('quiz.end-quiz') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    quiz_id: quizId,
                })
            })
            .then(res => res.json())
            .then(res => {console.log(res)});
    }
    if(@json($quiz->is_finished)){
        switchState("end")
        sendEnded()
    }
});
</script>

@endsection
