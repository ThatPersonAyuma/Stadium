@extends('layouts.dashboard')
@section('title', 'Join Quiz')

@section('styles')
    @vite(['resources/js/echo.js'])
@endsection

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>

    <div class="relative z-10 mx-auto max-w-3xl space-y-8">
        <x-dashboard-header title="Join Quiz" />

        <!-- Input Kode Quiz -->
        <div class="bg-white/10 rounded-2xl p-6 md:p-8 shadow-lg">
            <h3 class="text-xl font-bold mb-4">Masukkan Kode Quiz</h3>
            <form id="joinQuizForm" class="flex flex-col gap-4" action="{{ asset(route('quiz.post-register')) }}" method="post">
                @csrf
                <input type="text" id="quizCode" placeholder="Masukkan kode quiz" name="quiz_code"
                    class="rounded-lg border border-white/30 bg-white/10 px-4 py-3 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                <button type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-3 font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-blue-500">
                    Bergabung
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('joinQuizForm');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressStatus = document.getElementById('progressStatus');

    // form.addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     const code = document.getElementById('quizCode').value.trim();

    //     if(!code) return alert('Masukkan kode quiz terlebih dahulu!');

    //     // Simulasi join quiz
    //     progressBar.style.width = '100%';
    //     progressPercent.textContent = '100%';
    //     progressStatus.textContent = 'Joined';
    //     alert(`Berhasil bergabung ke quiz dengan kode: ${code}`);
    // });
});
</script>
@endsection
