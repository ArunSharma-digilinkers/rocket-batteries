@php
    $newCount = \App\Models\Enquiry::where('status', 'new')->count();
    $pendingWarranties = \App\Models\Warranty::where('status', 'pending')->count();
@endphp

<aside class="admin-sidebar" :class="sidebarOpen ? 'is-open' : ''" id="adminSidebar">
    <div class="admin-sidebar__brand">
        <a href="{{ route('admin.dashboard') }}">
            <span class="admin-sidebar__logo"><img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}"></span>
            <span><strong>Rocket Admin</strong><small>Control centre</small></span>
        </a>
        <button type="button" @click="sidebarOpen = false" aria-label="Close navigation"><i class="bi bi-x-lg"></i></button>
    </div>

    <nav class="admin-sidebar__nav" aria-label="Admin navigation">
        <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
            <span><i class="bi bi-grid-1x2-fill"></i></span><strong>Dashboard</strong>
        </a>

        <div class="admin-nav-group">
            <span class="admin-nav-group__label">Catalog</span>
            <a class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><span><i class="bi bi-folder-fill"></i></span><strong>Categories</strong></a>
            <a class="admin-nav-link {{ request()->routeIs('admin.series.*') ? 'active' : '' }}" href="{{ route('admin.series.index') }}"><span><i class="bi bi-collection-fill"></i></span><strong>Series</strong></a>
            <a class="admin-nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}"><span><i class="bi bi-sliders"></i></span><strong>Attributes</strong></a>
            <a class="admin-nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}"><span><i class="bi bi-diagram-3-fill"></i></span><strong>Applications</strong></a>
            <a class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><span><i class="bi bi-battery-full"></i></span><strong>Products</strong></a>
        </div>

        <div class="admin-nav-group">
            <span class="admin-nav-group__label">Content</span>
            <a class="admin-nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}" href="{{ route('admin.blog.index') }}"><span><i class="bi bi-journal-richtext"></i></span><strong>Blog</strong></a>
            <a class="admin-nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}" href="{{ route('admin.gallery.index') }}"><span><i class="bi bi-images"></i></span><strong>Gallery</strong></a>
        </div>

        <div class="admin-nav-group">
            <span class="admin-nav-group__label">Customer Activity</span>
            <a class="admin-nav-link {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}" href="{{ route('admin.enquiries.index') }}">
                <span><i class="bi bi-chat-left-text-fill"></i></span><strong>Enquiries</strong>@if ($newCount)<small class="admin-nav-link__count">{{ $newCount }}</small>@endif
            </a>
            <a class="admin-nav-link {{ request()->routeIs('admin.warranties.*') ? 'active' : '' }}" href="{{ route('admin.warranties.index') }}">
                <span><i class="bi bi-shield-check"></i></span><strong>Warranties</strong>@if ($pendingWarranties)<small class="admin-nav-link__count">{{ $pendingWarranties }}</small>@endif
            </a>
            <a class="admin-nav-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}" href="{{ route('admin.subscribers.index') }}"><span><i class="bi bi-people-fill"></i></span><strong>Subscribers</strong></a>
        </div>
    </nav>

    <div class="admin-sidebar__help">
        <span><i class="bi bi-lightning-charge-fill"></i></span>
        <div><strong>Rocket Batteries</strong><small>Powering progress since 1952</small></div>
    </div>
</aside>
