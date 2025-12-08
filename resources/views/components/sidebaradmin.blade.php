<div class="sidebar">
    <a href="{{ route('dashboard.admin') }}"
       class="sidebar-item {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/home.png" class="icon">
    </a>

    <a href="{{ route('admin.manajemen-teachers') }}"
       class="sidebar-item {{ request()->routeIs('admin.manajemen-teachers') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/course.png" class="icon" alt="Courses">
    </a>

    <a href="{{ route('admin.manajemen-course.index') }}" 
       class="sidebar-item {{ request()->routeIs('admin.manajemen-course.index') || request()->routeIs('admin.manajemen-course.show')  || request()->routeIs('admin.manajemen-course.preview') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/leaderboard.png" class="icon" alt="Leaderboard">
    </a>

    <a href="{{ route('admin.manajemen-quiz.index') }}" 
     class="sidebar-item {{ request()->routeIs('admin.manajemen-quiz.index')  || request()->routeIs('admin.manajemen-quiz.show') ? 'active' : '' }}">
        <img src="/assets/icons/sidebar-icons/pvp.png" class="icon" alt="PvP">
    </a>

    <form action="{{ route('logout') }}" method="POST" class="sidebar-item small-icon sidebar-bottom">
        @csrf
        <button type="submit">
            <img src="/assets/icons/sidebar-icons/logout.png" class="icon" alt="logout">
        </button>
    </form>

</div>
