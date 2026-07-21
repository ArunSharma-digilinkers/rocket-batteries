<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layouts.partials.head')
</head>
<body class="admin-body" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <div class="admin-shell">
        @include('admin.layouts.partials.sidebar')

        <button type="button" class="admin-sidebar-backdrop" x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen = false" aria-label="Close navigation"></button>

        <div class="admin-main">
            @include('admin.layouts.partials.topbar')

            <main class="admin-content">
                <div class="admin-page-head">
                    <div>
                        <span class="admin-page-head__eyebrow">Rocket Batteries · Administration</span>
                        <h1>@yield('title')</h1>
                    </div>
                    <div class="admin-page-head__actions">@yield('actions')</div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success admin-alert"><i class="bi bi-check-circle-fill"></i><span>{{ session('status') }}</span></div>
                @endif

                @yield('content')
            </main>

            <footer class="admin-footer">
                <span>&copy; {{ now()->year }} {{ config('app.name') }}</span>
                <span>Administration panel</span>
            </footer>
        </div>
    </div>

    @include('admin.layouts.partials.scripts')
</body>
</html>
