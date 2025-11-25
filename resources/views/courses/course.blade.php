@extends('layouts.dashboard')
@section('title', 'Course')

@section('content')
<div class="relative min-h-[calc(100vh-120px)] px-8 pt-6 pb-8 md:pt-8 md:pb-10 lg:px-16 xl:px-20 text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(120%_80%_at_18%_10%,rgba(0,64,168,0.25),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_82%_0%,rgba(0,46,135,0.35),transparent_60%)]"></div>

    <div class="relative space-y-6">
        <x-dashboard-header title="Course" />

        <div class="flex flex-wrap gap-3">
            <button type="button" data-filter-btn data-filter="all"
                class="inline-flex items-center gap-2 rounded-xl border border-[#c8d0f5] bg-white text-slate-900 px-4 py-3 font-semibold shadow-lg transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-0">
                <span class="w-5 h-5 text-current" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5v-15A1.5 1.5 0 0 1 5.5 3h9.75A1.75 1.75 0 0 1 17 4.75V20"></path>
                        <path d="M4 19.5A1.5 1.5 0 0 1 5.5 18h11.75c.414 0 .75.336.75.75v.75"></path>
                        <path d="M8 7h5"></path>
                        <path d="M8 11h5"></path>
                    </svg>
                </span>
                <span>All Course</span>
                <span data-count
                    class="rounded-full bg-indigo-600 px-2.5 py-1 text-xs font-black leading-none text-white">
                    {{ $summary['all'] ?? 0 }}
                </span>
            </button>

            <button type="button" data-filter-btn data-filter="activity"
                class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 font-semibold text-white shadow-lg transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-0">
                <span class="w-5 h-5 text-current" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 17l6-10 5 9 4-7"></path>
                    </svg>
                </span>
                <span>Activity</span>
                <span data-count
                    class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-black leading-none text-white">
                    {{ $summary['activity'] ?? 0 }}
                </span>
            </button>

            <button type="button" data-filter-btn data-filter="completed"
                class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-3 font-semibold text-white shadow-lg transition focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-0">
                <span class="w-5 h-5 text-current" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 11 3 3L22 4"></path>
                        <path d="M21 12v6a2 2 0 0 1-2 2H5.6a2 2 0 0 1-1.95-1.56L2 9"></path>
                        <path d="M7 10H3.4"></path>
                    </svg>
                </span>
                <span>Completed</span>
                <span data-count
                    class="rounded-full bg-white/15 px-2.5 py-1 text-xs font-black leading-none text-white">
                    {{ $summary['completed'] ?? 0 }}
                </span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($courses as $course)
                @php
                    $statusLabel = $course->status === 'completed'
                        ? 'Completed'
                        : ($course->status === 'activity' ? 'Activity' : 'New');
                @endphp

                <div data-course-card data-status="{{ $course->status }}"
                    @if ($course->status === 'activity') data-href="{{ route('course.detail', $course->id) }}" @endif
                    class="group relative flex h-full flex-col overflow-hidden rounded-2xl "
                    style="background: linear-gradient(150deg, {{ $course->color }}, #0b1f53);">
                    <div class="h-28 bg-slate-100/90 mix-blend-screen"></div>

                    <div class="flex flex-1 flex-col gap-3 p-4 md:p-5 bg-gradient-to-b from-white/5 to-black/10">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="m-0 text-xl font-black leading-tight tracking-tight">{{ $course->title }}</h3>
                            <span class="inline-flex items-center rounded-full border border-white/30 bg-white/20 px-3 py-1 text-xs font-extrabold uppercase tracking-wide text-white">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        @if ($course->status !== 'new')
                            <div class="h-2 w-full overflow-hidden rounded-full bg-white/25 shadow-inner">
                                <div class="h-full bg-gradient-to-r from-amber-300 to-orange-500"
                                    style="width: {{ $course->progress }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-sm font-semibold opacity-90">
                                <span>{{ $course->progress }}%</span>
                                <span>{{ $course->status === 'completed' ? 'Finished' : 'In progress' }}</span>
                            </div>
                        @else
                            <p class="m-0 text-sm font-semibold opacity-90">Ready to start this course.</p>
                        @endif

                        <a href="{{ route('course.detail', $course->id) }}"
                           class="inline-flex w-full items-center justify-center rounded-lg bg-white/20 px-3 py-3 text-sm font-extrabold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-white/30">
                            {{ $course->cta }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = Array.from(document.querySelectorAll('[data-filter-btn]'));
        const cards = Array.from(document.querySelectorAll('[data-course-card]'));

        const activate = (filter) => {
            buttons.forEach((btn) => {
                const isActive = btn.dataset.filter === filter;
                const countEl = btn.querySelector('[data-count]');

                btn.classList.toggle('bg-white', isActive);
                btn.classList.toggle('text-slate-900', isActive);
                btn.classList.toggle('border-[#c8d0f5]', isActive);
                btn.classList.toggle('shadow-xl', isActive);

                btn.classList.toggle('bg-white/10', !isActive);
                btn.classList.toggle('text-white', !isActive);
                btn.classList.toggle('border-white/20', !isActive);

                if (countEl) {
                    countEl.classList.toggle('bg-indigo-600', isActive);
                    countEl.classList.toggle('text-white', isActive);
                    countEl.classList.toggle('bg-white/15', !isActive);
                }
            });

            cards.forEach((card) => {
                const status = card.dataset.status;
                const shouldShow = filter === 'all' || status === filter;
                card.classList.toggle('hidden', !shouldShow);
            });
        };

        activate('all');
        buttons.forEach((btn) =>
            btn.addEventListener('click', () => activate(btn.dataset.filter || 'all'))
        );
    });
</script>
@endsection
