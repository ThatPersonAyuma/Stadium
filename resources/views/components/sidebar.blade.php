
@php
    $user = Illuminate\Support\Facades\Auth::user();
@endphp
<div class="sidebar">
    <a href="{{ route('dashboard.index') }}"
       class="sidebar-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/icons/sidebar-icons/home.png') }}" class="icon">
    </a>
    @if ($user?->role == App\Enums\UserRole::ADMIN)
        <a href="{{ route('admin.manajemen.teachers') }}"
            class="sidebar-item {{ request()->routeIs('admin.manajemen.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/icons/sidebar-icons/accmanag.png') }}" class="icon" alt="Courses">
    </a>
    @endif
    <a href="{{ route('course.index') }}"
       class="sidebar-item {{ request()->routeIs('course.*') || request()->routeIs('lesson.*') || request()->routeIs('teacher.courses.*') || request()->routeIs('admin.manajemen-course.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/icons/sidebar-icons/course.png') }}" class="icon" alt="Courses">
    </a>
    <a href="{{ route('leaderboard.index') }}" 
       class="sidebar-item {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/icons/sidebar-icons/leaderboard.png') }}" class="icon" alt="Leaderboard">
    </a>
    <a href="{{ route('quiz.index') }}" class="sidebar-item {{ request()->routeIs('quiz.*') || request()->routeIs('admin.manajemen-quiz.*') ? 'active' : '' }}">
        <img src="{{ asset('assets/icons/sidebar-icons/pvp.png') }}" class="icon" alt="PvP">
    </a>
    <a href="{{ route('profile.index') }}" class="sidebar-item {{ request()->is('profile*') ? 'active' : '' }}">
        <img src="{{ asset('assets/icons/sidebar-icons/profile.png') }}" class="icon" alt="profile">
    </a>
    <form action="{{ route('logout') }}" method="POST" class="sidebar-item small-icon sidebar-bottom">
        @csrf
        <button type="submit">
            <img src="{{ asset('assets/icons/sidebar-icons/logout.png') }}" class="icon" alt="logout">
        </button>
    </form>

</div>
