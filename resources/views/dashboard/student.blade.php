@extends('layouts.dashboard')
@section('title', 'Student Dashboard')

@section('content')
@php
    $courses = $courses ?? collect();
    $recentActivity = $recentActivity ?? [];
    $leaderboard = $leaderboard ?? collect();
@endphp
<div class="relative min-h-[calc(100vh-120px)] px-6 pt-10 pb-14 md:px-10 lg:px-16 xl:px-20 text-white overflow-hidden">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(110%_70%_at_12%_10%,rgba(0,46,135,0.35),transparent_55%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(130%_80%_at_88%_0%,rgba(0,153,255,0.25),transparent_60%)]"></div>
    <div class="hello-bg">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 800" preserveAspectRatio="none">
            <path fill="#002872" d="M0,160 Q240,280 480,160 T960,160 T1440,160 L1440,800 L0,800 Z" />
            {{-- <path fill="#001E5C" opacity="0.9" d="M0,600 Q360,400 720,600 T1440,600 L1440,800 L0,800 Z" /> --}}
        </svg>
    </div>

    <div class="relative z-10 mx-auto max-w-6xl">
        <div class="dashboard-content space-y-8">
            <div class="hello-section">
                <div class="hello-header-wrapper">
                    <x-dashboard-header />
                </div>

                

                <div class="hello-box">
                    <img src="/assets/icons/mascotss.png" class="hello-mascot" alt="Mascot">

                    <div class="hello-text">
                        <h2 class="hello-title">Hello</h2>
                        <h1 class="hello-name">{{ $user->name ?? 'Student' }}</h1>
                    </div>
                </div>

                <h2 class="recent-title-inside">Recent Activity</h2>

                <div class="recent-activity-container inside-hero">
                    @forelse ($recentActivity as $activity)
                        <div class="recent-card">
                            <h4 class="recent-title">{{ $activity['title'] }}</h4>
                            <p class="recent-time">{{ $activity['time'] }}</p>
                        </div>
                    @empty
                        <p class="recent-time">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </div>

            <div class="course-container">
                <h2 class="section-t2 mt-10">Course</h2>

                @forelse ($courses as $course)
                    @php $progress = $course->pivot->progress ?? 0; @endphp
                    <div class="course-card" style="background: {{ $course->color }}">
                        <div class="course-thumb"></div>

                        <div class="course-info">
                            <h3 class="course-title">{{ $course->name }}</h3>
                            <p class="course-topic">Topic: {{ $course->topic }}</p>
                            <div class="course-progress">
                                <div class="course-progress-fill" style="width: {{ $progress }}%;"></div>
                            </div>

                            <span class="course-progress-text">
                                {{ $progress }}% Complete
                            </span>

                            <a href="#" class="course-btn">
                                Continue
                            </a>
                        </div>

                    </div>
                @empty
                    <p class="recent-time">Belum ada course terdaftar.</p>
                @endforelse

            </div>

            <div class="leaderboard-box">
                <h2 class="section-t2 mt-10">Leaderboard</h2>
                <br>
                <div class="leaderboard-container">
                    <div class="leader-podium">
                        <div class="podium podium-2">
                            <img src="{{ $leaderboard[1]->avatar ?? '/assets/icons/mascotss.png' }}" class="podium-avatar">
                            <span>{{ $leaderboard[1]->name ?? '-' }}</span>
                            <strong>{{ $leaderboard[1]->score ?? 0 }}</strong>
                        </div>

                        <div class="podium podium-1">
                            <img src="{{ $leaderboard[0]->avatar ?? '/assets/icons/mascotss.png' }}" class="podium-avatar">
                            <span>{{ $leaderboard[0]->name ?? '-' }}</span>
                            <strong>{{ $leaderboard[0]->score ?? 0 }}</strong>
                        </div>

                        <div class="podium podium-3">
                            <img src="{{ $leaderboard[2]->avatar ?? '/assets/icons/mascotss.png' }}" class="podium-avatar">
                            <span>{{ $leaderboard[2]->name ?? '-' }}</span>
                            <strong>{{ $leaderboard[2]->score ?? 0 }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
