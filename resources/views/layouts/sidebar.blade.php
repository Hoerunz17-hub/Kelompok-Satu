<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-category">Navigation</li>
        <li class="nav-item {{ request()->is('/') || request()->is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/') }}">
                <span class="icon-bg"><i class="mdi mdi-home
 menu-icon"></i></span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item {{ request()->is('service') || request()->is('service/*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/service') }}">
                <span class="icon-bg"><i class="mdi mdi-wrench menu-icon"></i></span>
                <span class="menu-title">Service</span>
            </a>
        </li>

        <li class="nav-item nav-category">Master Data</li>

        <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/user') }}">
                <span class="icon-bg"><i class="mdi mdi-account menu-icon"></i></span>
                <span class="menu-title">User</span>
            </a>
        </li>



        <li class="nav-item {{ request()->is('laptop*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/laptop') }}">
                <span class="icon-bg"><i class="mdi mdi-laptop menu-icon"></i></span>
                <span class="menu-title">Laptop</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('serviceitem') || request()->is('serviceitem/*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/serviceitem') }}">
                <span class="icon-bg"><i class="mdi mdi-briefcase menu-icon"></i></span>
                <span class="menu-title">Service Item</span>
            </a>
        </li>

        <li class="nav-item sidebar-user-actions">
            <div class="sidebar-user-menu">
                <a href="{{ route('logout') }}" class="nav-link d-flex align-items-center"
                    onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="mdi mdi-logout menu-icon"></i>
                    <span class="menu-title">Log Out</span>
                </a>
            </div>

            {{-- hidden form untuk logout --}}
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</nav>
