<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="adminSidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-white-50 text-uppercase small">
            Catalog
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                    Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.series.*') ? 'active' : '' }}" href="{{ route('admin.series.index') }}">
                    Series
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
                    Attributes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}" href="{{ route('admin.applications.index') }}">
                    Applications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    Products
                </a>
            </li>
        </ul>
    </div>
</nav>
