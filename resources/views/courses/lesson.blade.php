@extends('layouts.dashboard')
@section('title', 'Lesson')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-10 md:pt-8 lg:px-16 xl:px-20 text-white overflow-hidden">
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

        <div class="relative overflow-hidden rounded-3xl bg-[#002f87] border border-white/15 shadow-2xl p-6 md:p-8">
            <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-blue-900/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-indigo-900/35 blur-3xl"></div>

            <div class="relative space-y-6">
                <div class="space-y-2">
                    <p class="text-xs md:text-sm uppercase tracking-[0.25em] font-extrabold text-white/70">
                        {{ $lesson['subtitle'] }}
                    </p>
                    <h2 class="text-3xl md:text-4xl font-black leading-tight drop-shadow-sm">
                        {{ $lesson['question'] }}
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                    @foreach ($lesson['options'] as $option)
                        <button
                            class="w-full rounded-3xl bg-[#99ca5c] text-white text-2xl md:text-3xl font-black py-5 md:py-6 shadow-[0_14px_30px_rgba(0,0,0,0.25)] transition transform hover:-translate-y-1 hover:shadow-[0_18px_36px_rgba(0,0,0,0.28)] focus:outline-none focus:ring-4 focus:ring-white/30">
                            {{ $option }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
