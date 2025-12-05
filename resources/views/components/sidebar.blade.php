<div class="sidebar">
    <a href="{{ route('dashboard.index') }}"
       class="sidebar-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/home.png" class="icon">
    </a>
    <a href="{{ route('course.index') }}"
       class="sidebar-item {{ request()->routeIs('course.*') || request()->routeIs('lesson.*') || request()->routeIs('teacher.courses.*') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/course.png" class="icon" alt="Courses">
    </a>
    <a href="#" class="sidebar-item">
        <img src="/assets/icons/sidebar-icons/leaderboard.png" class="icon" alt="Leaderboard">
    </a>
    <a href="{{ route('quiz.index') }}" class="sidebar-item {{ request()->routeIs('quiz.*') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/pvp.png" class="icon" alt="PvP">
    </a>
    <form action="{{ route('logout') }}" method="POST" class="sidebar-item small-icon sidebar-bottom">
        @csrf
        <button type="submit">
            <img src="/assets/icons/sidebar-icons/logout.png" class="icon" alt="logout">
        </button>
    </form>

</div>
