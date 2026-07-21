<header class="admin-topbar">
    <div class="admin-topbar__left">
        <button type="button" class="admin-menu-toggle" @click="sidebarOpen = true" aria-label="Open navigation">
            <i class="bi bi-list"></i>
        </button>
        <div class="admin-topbar__context">
            <span><i></i> System online</span>
            <small>{{ now()->format('l, d M Y') }}</small>
        </div>
    </div>

    <div class="admin-topbar__right">
        <a href="{{ route('home') }}" target="_blank" class="admin-view-site">
            <i class="bi bi-box-arrow-up-right"></i><span>View website</span>
        </a>
        <div class="admin-user">
            <span class="admin-user__avatar">{{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}</span>
            <span class="admin-user__details"><strong>{{ auth('admin')->user()->name }}</strong><small>{{ str_replace('_', ' ', auth('admin')->user()->role) }}</small></span>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="admin-logout" aria-label="Log out" title="Log out"><i class="bi bi-box-arrow-right"></i></button>
        </form>
    </div>
</header>
