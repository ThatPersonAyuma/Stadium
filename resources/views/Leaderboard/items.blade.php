@foreach ($students as $s)
<div class="recent-card !w-full !h-auto !flex-row !justify-start !gap-4 !py-3 !px-4 group 
            cursor-pointer hover:bg-white/10 hover:translate-x-1 transition-all duration-300">

    {{-- Rank --}}
    <div class="flex flex-col items-center justify-center min-w-[40px]">
        <span class="font-heading text-xl text-white/40 group-hover:text-[#EDB240]">
            #{{ $s->global_rank }}
        </span>
    </div>

    {{-- Avatar --}}
    <div class="w-10 h-10 rounded-lg border border-white/20 overflow-hidden bg-white/10 shrink-0">
        <img src="{{ asset($s->avatar_filename ?? 'assets/icons/mascotss.png') }}"
             class="w-full h-full object-cover">
    </div>

    {{-- Name --}}
    <div class="flex-1 flex flex-col justify-center overflow-hidden">
        <h4 class="recent-title text-base text-white font-bold truncate group-hover:text-[#4FB4F8] 
                   transition-colors">
            {{ $s->user->name }}
        </h4>
    </div>

    {{-- Score --}}
    <div class="shrink-0">
        <div class="bg-[#002872] border border-white/10 px-3 py-1.5 rounded-lg shadow-inner">
            <p class="font-mono font-bold text-[#EDB240] text-sm">
                {{ $s->experience }} XP
            </p>
        </div>
    </div>

</div>
@endforeach
