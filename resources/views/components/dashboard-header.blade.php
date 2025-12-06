@props(['title' => 'Dashboard', 'showPlant' => true])

<div class="header flex justify-between items-center w-full">
    <h1 class="header-title">{{ $title }}</h1>

    <div class="header-icons flex items-center gap-4">
        @if (Illuminate\Support\Facades\Auth::user()->role == App\Enums\UserRole::STUDENT)
            <div class="hp-heart">
                <img src="/assets/icons/heart.png" class="hp-heart-img">
                <span class="hp-number">{{ Illuminate\Support\Facades\Auth::user()->student->heart }}</span>
            </div>
        @endif
        <div class="icon-circle">
            <img src="{{ asset(App\Helpers\FileHelper::getAvatarPath(Illuminate\Support\Facades\Auth::user()->id)) }}" alt="Profile">
        </div>

    </div>
    
</div>
