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

        <div class="relative overflow-hidden rounded-3xl bg-[#002f87] border border-white/15 shadow-2xl p-6 md:p-8 space-y-6">
            <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-blue-900/40 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-indigo-900/35 blur-3xl"></div>

            <div class="relative space-y-2">
                <p class="text-xs md:text-sm uppercase tracking-[0.25em] font-extrabold text-white/70">
                    Modul {{ $lesson->order_index ?? '-' }}
                </p>
                <h2 class="text-3xl md:text-4xl font-black leading-tight drop-shadow-sm">
                    {{ $lesson->title }}
                </h2>
                <p class="text-sm opacity-80 m-0">{{ $lesson->description }}</p>
            </div>

            <div class="relative grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                @forelse ($lesson->contents as $content)
                    <div class="rounded-2xl border border-white/15 bg-white/5 p-4 shadow-xl space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] font-semibold text-white/60 m-0">Content {{ $content->order_index }}</p>
                                <h3 class="text-xl font-black leading-tight m-0">{{ $content->title }}</h3>
                            </div>
                            <span class="rounded-full bg-white/15 border border-white/25 px-3 py-1 text-xs font-semibold">
                                {{ $content->cards->count() }} Card
                            </span>
                        </div>

                        <div class="space-y-2">
                            @forelse ($content->cards as $card)
                                <div class="rounded-xl border border-white/15 bg-white/5 px-3 py-2 flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-xs font-bold">#{{ $card->order_index }}</span>
                                        <span class="font-semibold">Card {{ $card->id }}</span>
                                    </div>
                                    <a href="{{ route('lesson.show', ['courseId' => $lesson->course_id, 'lessonId' => $lesson->id]) }}#card-{{ $card->id }}"
                                       class="text-indigo-200 hover:text-white text-xs font-semibold">Lihat</a>
                                </div>
                            @empty
                                <p class="text-sm opacity-70 m-0">Belum ada card.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-sm opacity-75 col-span-full">Belum ada konten untuk lesson ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
