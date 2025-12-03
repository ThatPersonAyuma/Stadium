@props(['title' => 'Dashboard', 'showPlant' => true])

<div class="header flex justify-between items-center w-full">
    <h1 class="header-title">{{ $title }}</h1>

    <div class="header-icons flex items-center gap-4">
        <div class="hp-heart">
            <img src="/assets/icons/heart.png" class="hp-heart-img">
            <span class="hp-number">5</span>
        </div>
        <div class="icon-circle">
            <img src="{{ asset(App\Helpers\FileHelper::getAvatarPath(Illuminate\Support\Facades\Auth::user()->id)) }}" alt="Profile">
        </div>

    </div>
    
</div>

@if ($showPlant)
    <div class="plant-wrapper flex justify-end mt-3">
        <div class="icon-plant">
            <img src="/assets/icons/plant.png">
        </div>
    </div>
@endif
