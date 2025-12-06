@extends('layouts.dashboard')
@section('title', 'Leaderboard')

@section('content')

{{-- 1. WRAPPER UTAMA & BACKGROUND (Sama persis dengan Dashboard) --}}
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white overflow-hidden">
    
    {{-- Efek Cahaya Background --}}
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    
    {{-- SVG Gelombang --}}
    <div class="hello-bg">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 800" preserveAspectRatio="none">
            <path fill="#002872" d="M0,160 Q240,280 480,160 T960,160 T1440,160 L1440,800 L0,800 Z" />
        </svg>
    </div>

    {{-- 2. KONTEN (Layout Dashboard) --}}
    <div class="relative z-10 mx-auto max-w-6xl dashboard-content space-y-8">
        
        {{-- HEADER SECTION (Menggunakan Component biar SAMA PERSIS dengan Dashboard) --}}
        <div class="hello-section !pb-0 !min-h-0 !p-0">
            <div class="hello-header-wrapper !p-0 !mb-0">
                
                {{-- Panggil Component Header --}}
                <x-dashboard-header title="Leaderboard" :showPlant="false" />

            </div>
        </div>

        {{-- ===============================================================
             3. PODIUM SECTION (CUSTOM DESIGN KAMU)
             =============================================================== --}}
        <div class="mb-20 mt-16"> {{-- Tambah margin-top biar ga mepet header --}}
            <div class="flex justify-center items-end gap-2 sm:gap-4 md:gap-8">
                
                {{-- JUARA 2 (KIRI) --}}
                <div class="flex flex-col items-center relative w-1/3 max-w-[300px] mt-[250px] translate-x-[55px] group cursor-pointer">
                    {{-- Mahkota --}}
                    <img src="{{ asset('images/crown2.png') }}" class="absolute -top-[170px] left-[160px] max-w-[60px] z-20 drop-shadow-md">
                    {{-- Avatar --}}
                    <div class="absolute -top-[140px] z-10">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-gray-300 overflow-hidden bg-white/20 shadow-lg backdrop-blur-sm">
                            <img src="{{ $topThree[1]->avatar ?? asset('assets/icons/mascotss.png') }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    {{-- Gambar Podium --}}
                    <img src="{{ asset('images/rank2.png') }}" class="w-full object-contain drop-shadow-2xl z-0">
                    {{-- Info --}}
                    <div class="text-center mt-2 absolute bottom-4 w-full px-2">
                        <p class="font-bold text-xs sm:text-sm truncate text-white drop-shadow-md">{{ $topThree[1]->name ?? '-' }}</p>
                        <p class="text-[10px] sm:text-xs text-blue-100 font-bold">{{ $topThree[1]->score ?? 0 }} XP</p>
                    </div>
                    {{-- Angka Gede --}}
                    <h1 class="text-[6rem] sm:text-[8rem] font-black text-white/20 absolute bottom-[40px] left-1/2 -translate-x-1/2 z-0 select-none">2</h1>
                </div>

                {{-- JUARA 1 (TENGAH) --}}
                <div class="flex flex-col items-center relative w-1/3 max-w-[300px] -mt-10 z-10 group cursor-pointer">
                    {{-- Mahkota --}}
                    <img src="{{ asset('images/crown1.png') }}" class="absolute -top-[220px] right-[70px] w-16 z-20 animate-bounce drop-shadow-xl" onerror="this.style.display='none'">
                    {{-- Avatar --}}
                    <div class="absolute -top-[180px] z-10">
                        <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full border-4 border-[#EDB240] overflow-hidden bg-[#EDB240]/20 shadow-xl ring-4 ring-[#EDB240]/30 backdrop-blur-sm">
                            <img src="{{ $topThree[0]->avatar ?? asset('assets/icons/mascotss.png') }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    {{-- Gambar Podium --}}
                    <img src="{{ asset('images/rank1.png') }}" class="w-full object-contain drop-shadow-2xl z-0">
                    {{-- Info --}}
                    <div class="text-center mt-2 absolute bottom-6 w-full px-2">
                        <p class="font-bold text-sm sm:text-base truncate text-[#EDB240] drop-shadow-md">{{ $topThree[0]->name ?? '-' }}</p>
                        <p class="text-xs sm:text-sm text-white font-bold">{{ $topThree[0]->score ?? 0 }} XP</p>
                    </div>
                    {{-- Angka Gede --}}
                    <h1 class="text-[8rem] sm:text-[12rem] font-black text-white/20 absolute bottom-[40px] left-1/2 -translate-x-1/2 z-0 select-none">1</h1>
                </div>

                {{-- JUARA 3 (KANAN) --}}
                <div class="flex flex-col items-center relative w-1/3 max-w-[300px] -translate-x-[50px] group cursor-pointer">
                    {{-- Mahkota --}}
                    <img src="{{ asset('images/crown3.png') }}" class="absolute -top-[155px] right-[85px] w-[60px] z-20 drop-shadow-md">
                    {{-- Avatar --}}
                    <div class="absolute -top-[120px] z-10">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-orange-400 overflow-hidden bg-white/20 shadow-lg backdrop-blur-sm">
                            <img src="{{ $topThree[2]->avatar ?? asset('assets/icons/mascotss.png') }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    {{-- Gambar Podium --}}
                    <img src="{{ asset('images/rank3.png') }}" class="w-full object-contain drop-shadow-2xl z-0">
                    {{-- Info --}}
                    <div class="text-center mt-2 absolute bottom-4 w-full px-2">
                        <p class="font-bold text-xs sm:text-sm truncate text-white drop-shadow-md">{{ $topThree[2]->name ?? '-' }}</p>
                        <p class="text-[10px] sm:text-xs text-orange-200 font-bold">{{ $topThree[2]->score ?? 0 }} XP</p>
                    </div>
                    {{-- Angka Gede --}}
                    <h1 class="text-[6rem] sm:text-[7rem] font-black text-white/20 absolute bottom-[18px] left-1/2 -translate-x-1/2 z-0 select-none">3</h1>
                </div>

            </div>
        </div>

        {{-- ===============================================================
             4. LIST KATEGORI (CUSTOM DESIGN KAMU - VERTIKAL)
             =============================================================== --}}
        <div class="grid gap-8 pb-10">
            @foreach($categories as $title => $players)
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6 backdrop-blur-sm">
                
                {{-- Judul Kategori --}}
                <h3 class="section-t2 text-2xl mb-4 text-[#EDB240] pl-2 border-l-4 border-[#EDB240]">
                    {{ $title }}
                </h3>
                
                {{-- List Player --}}
                <div class="flex flex-col gap-3">
                    @forelse($players as $player)
                    <div class="recent-card !w-full !h-auto !flex-row !justify-start !gap-4 !py-3 !px-4 group cursor-pointer hover:bg-white/10 hover:translate-x-1 transition-all duration-300">
                        {{-- Rank --}}
                        <div class="flex flex-col items-center justify-center min-w-[40px]">
                            <span class="font-heading text-xl text-white/40 group-hover:text-[#EDB240]">
                                #{{ $loop->parent->index * 3 + $loop->iteration + 3 }}
                            </span> 
                        </div>
                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-lg border border-white/20 overflow-hidden bg-white/10 shrink-0">
                            <img src="{{ $player->avatar ?? asset('assets/icons/mascotss.png') }}" class="w-full h-full object-cover">
                        </div>
                        {{-- Nama --}}
                        <div class="flex-1 flex flex-col justify-center overflow-hidden">
                            <h4 class="recent-title text-base text-white font-bold truncate group-hover:text-[#4FB4F8] transition-colors">
                                {{ $player->name }}
                            </h4>
                        </div>
                        {{-- Score --}}
                        <div class="shrink-0">
                            <div class="bg-[#002872] border border-white/10 px-3 py-1.5 rounded-lg shadow-inner">
                                <p class="font-mono font-bold text-[#EDB240] text-sm">{{ $player->score }} XP</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-white/30 italic bg-black/20 rounded-xl">
                        Belum ada player di kategori ini.
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection