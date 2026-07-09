<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('admin.dashboard') }}">
        {{ config('app.name') }} Admin
    </a>
    <button class="navbar-toggler d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav flex-row ms-auto me-3">
        <div class="nav-item text-nowrap d-flex align-items-center">
            <span class="text-white small me-3">{{ auth('admin')->user()->name }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>
    </div>
</header>
