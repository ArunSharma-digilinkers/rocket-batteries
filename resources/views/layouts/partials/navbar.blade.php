@php
    $navCategories = \App\Models\Category::navigationTree();
    $navPhone = \App\Models\Setting::get('contact_phone');
@endphp

<header
    class="site-header sticky-top"
    x-data="{ scrolled: false, mobileOpen: false }"
    x-init="
        window.addEventListener('scroll', () => scrolled = window.scrollY > 30);
        $refs.mobileMenu.addEventListener('shown.bs.collapse', () => mobileOpen = true);
        $refs.mobileMenu.addEventListener('hidden.bs.collapse', () => mobileOpen = false);
    "
    :class="{ 'is-scrolled': scrolled, 'is-menu-open': mobileOpen }"
>
    <nav class="navbar navbar-expand-lg container site-navbar" aria-label="Main navigation">
        <a class="navbar-brand site-brand" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" height="40">
            <span class="site-brand__meta d-none d-xl-flex"><small>Powering progress</small><strong>Since 1952</strong></span>
        </a>

        <button
            class="navbar-toggler site-menu-toggle"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            :aria-expanded="mobileOpen.toString()"
            aria-label="Toggle navigation"
            @click="mobileOpen = !mobileOpen"
        >
            <span></span><span></span><span></span>
        </button>

        <div class="collapse navbar-collapse site-navbar__collapse" id="mainNavbar" x-ref="mobileMenu">
            <div class="mobile-menu__intro d-lg-none">
                <span><i class="bi bi-lightning-charge-fill"></i></span>
                <div><small>Explore Rocket Batteries</small><strong>Powering progress since 1952</strong></div>
            </div>

            <ul class="navbar-nav ms-auto align-items-lg-center site-navbar__nav">
                <li class="nav-item"><a class="nav-link main-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}"><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-house-door-fill"></i></span><span class="mobile-nav-link__label">Home</span><i class="bi bi-chevron-right mobile-nav-link__chevron d-lg-none"></i></a></li>
                <li class="nav-item"><a class="nav-link main-nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-building"></i></span><span class="mobile-nav-link__label">About</span><i class="bi bi-chevron-right mobile-nav-link__chevron d-lg-none"></i></a></li>

                <li
                    class="nav-item dropdown product-nav"
                    x-data="{
                        open: false,
                        closeTimer: null,
                        showMenu() {
                            clearTimeout(this.closeTimer);
                            this.open = true;
                        },
                        scheduleClose() {
                            clearTimeout(this.closeTimer);
                            this.closeTimer = setTimeout(() => this.open = false, 240);
                        },
                        closeMenu() {
                            clearTimeout(this.closeTimer);
                            this.open = false;
                        }
                    }"
                    @mouseenter="if (window.innerWidth >= 992) showMenu()"
                    @mouseleave="if (window.innerWidth >= 992) scheduleClose()"
                    @click.outside="closeMenu()"
                    @keydown.escape.window="closeMenu()"
                >
                    <button
                        type="button"
                        class="nav-link main-nav-link dropdown-toggle {{ request()->routeIs('products.*', 'categories.*', 'series.*') ? 'active' : '' }}"
                        :class="{ 'is-open': open }"
                        @click="clearTimeout(closeTimer); open = !open"
                        :aria-expanded="open.toString()"
                    ><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-battery-charging"></i></span><span class="mobile-nav-link__label">Products</span><i class="bi bi-chevron-down mobile-nav-link__chevron mobile-nav-link__chevron--dropdown d-lg-none"></i></button>

                    <div
                        class="dropdown-menu product-menu"
                        :class="{ show: open }"
                        x-show="open"
                        x-transition.opacity.duration.150ms
                        x-cloak
                        @mouseenter="if (window.innerWidth >= 992) showMenu()"
                        @mouseleave="if (window.innerWidth >= 992) scheduleClose()"
                    >
                        <div class="product-menu__head">
                            <div><span>Explore the range</span><strong>Find the right power solution.</strong></div>
                            <a href="{{ route('products.index') }}">All products <i class="bi bi-arrow-right"></i></a>
                        </div>
                        <div class="product-menu__body">
                            <div class="product-menu__categories">
                                @foreach ($navCategories as $category)
                                    <div class="product-menu__category">
                                        <h3>{{ $category->name }}</h3>
                                        @forelse ($category->series as $series)
                                            <a href="{{ route('series.show', $series) }}">
                                                <span><i class="bi bi-battery-full"></i></span>
                                                <span><strong>{{ $series->name }} Series</strong><small>Products &amp; specifications</small></span>
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        @empty
                                            <small class="text-muted">Products coming soon.</small>
                                        @endforelse
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('home') }}#ev-products" class="product-menu__spotlight">
                                <span>Featured EV range</span>
                                <img src="{{ asset('img/ev-5200.png') }}" alt="Rocket EV-5200 battery">
                                <strong>Built to keep you moving.</strong>
                                <small>Explore EV batteries <i class="bi bi-arrow-up-right"></i></small>
                            </a>
                        </div>
                    </div>
                </li>

                <li class="nav-item"><a class="nav-link main-nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}"><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-images"></i></span><span class="mobile-nav-link__label">Gallery</span><i class="bi bi-chevron-right mobile-nav-link__chevron d-lg-none"></i></a></li>
                <li class="nav-item"><a class="nav-link main-nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}" href="{{ route('blog.index') }}"><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-journal-richtext"></i></span><span class="mobile-nav-link__label">Blog</span><i class="bi bi-chevron-right mobile-nav-link__chevron d-lg-none"></i></a></li>
                <li class="nav-item"><a class="nav-link main-nav-link {{ request()->routeIs('warranty') ? 'active' : '' }}" href="{{ route('warranty') }}"><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-shield-check"></i></span><span class="mobile-nav-link__label">Warranty</span><i class="bi bi-chevron-right mobile-nav-link__chevron d-lg-none"></i></a></li>
                <li class="nav-item"><a class="nav-link main-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}"><span class="mobile-nav-link__icon d-lg-none"><i class="bi bi-chat-dots-fill"></i></span><span class="mobile-nav-link__label">Contact</span><i class="bi bi-chevron-right mobile-nav-link__chevron d-lg-none"></i></a></li>
                <li class="nav-item site-navbar__cta">
                    <a class="main-nav-cta" href="{{ route('home') }}#contact-quote"><span>Get Free Quote</span><i class="bi bi-arrow-up-right"></i></a>
                </li>
            </ul>

            <div class="mobile-menu__footer d-lg-none">
                <span>Need help choosing a battery?</span>
                <div>
                    @if ($navPhone)
                        <a href="tel:{{ $navPhone }}"><i class="bi bi-telephone-fill"></i><span><small>Call our team</small><strong>{{ $navPhone }}</strong></span></a>
                    @endif
                    <a href="{{ route('contact') }}"><i class="bi bi-arrow-up-right"></i><span><small>Send an enquiry</small><strong>Contact experts</strong></span></a>
                </div>
            </div>
        </div>
    </nav>

    <button
        type="button"
        class="site-mobile-backdrop d-lg-none"
        x-show="mobileOpen"
        x-transition.opacity
        x-cloak
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
        @click="mobileOpen = false"
        aria-label="Close navigation"
    ></button>
</header>
