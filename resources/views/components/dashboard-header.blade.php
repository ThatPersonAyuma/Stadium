@props([
    'title' => 'Dashboard', 'showPlant' => true,
    'rankName' => 'Novice', 
    'size' => 'w-24 h-24',  
    'bgColor' => 'bg-gradient-to-br from-yellow-400 to-orange-500',
    'textColor' => 'text-white' 
])

@php
    $user = Illuminate\Support\Facades\Auth::user();
@endphp

<div class="header flex justify-between items-center w-full">
    <h1 class="header-title">{{ $title }}</h1>

    <div class="header-icons flex items-center gap-4">
        @if ($user?->role == App\Enums\UserRole::STUDENT)
            <div class="flex items-center justify-center rounded-lg shadow-lg $size $bgColor">
                <span class="font-bold text-center text-sm md:text-base lg:text-lg {{ $textColor }}">
                    {{ $user->student->rank?->title }}
                </span>
            </div>
            <div class="hp-heart">
                <img src="/assets/icons/heart.png" class="hp-heart-img">
                <span class="hp-number">{{ Illuminate\Support\Facades\Auth::user()->student->heart }}</span>
            </div>
        @endif
        <div class="icon-circle">
            <img src="{{ asset(App\Helpers\FileHelper::getAvatarPath(Illuminate\Support\Facades\Auth::user()?->id)) }}" alt="Profile">
        </div>

    </div>
    
</div>
