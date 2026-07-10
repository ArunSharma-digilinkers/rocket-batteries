@php
    $navCategories = \App\Models\Category::where('status', true)->orderBy('sort_order')->with('series')->get();
@endphp

<header class="border-bottom">
    <nav class="navbar navbar-expand-lg container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">{{ config('app.name') }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('products.*', 'categories.*', 'series.*') ? 'active' : '' }}" href="{{ route('products.index') }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Catalog
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('products.index') }}">All Products</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach ($navCategories as $category)
                            <li><h6 class="dropdown-header">{{ $category->name }}</h6></li>
                            @foreach ($category->series as $series)
                                <li><a class="dropdown-item ps-4" href="{{ route('series.show', $series) }}">{{ $series->name }}</a></li>
                            @endforeach
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
