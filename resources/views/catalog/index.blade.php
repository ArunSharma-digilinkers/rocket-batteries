@extends('layouts.app')

@section('title', 'Product Catalog — ' . config('app.name'))
@section('meta_description', 'Explore Rocket Batteries for electric mobility, industrial backup, telecom, solar, UPS, and demanding energy applications.')

@section('content')
    {{-- Catalog hero --}}
    <section class="catalog-page-hero">
        <div class="catalog-page-hero__ambient" aria-hidden="true">
            <span class="catalog-page-hero__glow catalog-page-hero__glow--orange"></span>
            <span class="catalog-page-hero__glow catalog-page-hero__glow--blue"></span>
            <span class="catalog-page-hero__orbit catalog-page-hero__orbit--one"></span>
            <span class="catalog-page-hero__orbit catalog-page-hero__orbit--two"></span>
        </div>

        <div class="container position-relative">
            <nav aria-label="breadcrumb" class="reveal">
                <ol class="breadcrumb modern-breadcrumb mb-5">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Products</li>
                </ol>
            </nav>

            <div class="row align-items-center g-5">
                <div class="col-lg-7 reveal">
                    <span class="hero-kicker mb-3 d-inline-flex align-items-center">
                        <i class="bi bi-lightning-charge-fill"></i>
                        Complete battery portfolio
                    </span>
                    <h1 class="catalog-page-hero__title">Power engineered for<br><span>every application.</span></h1>
                    <p class="catalog-page-hero__copy">
                        Explore reliable Rocket battery solutions for electric mobility, industrial backup,
                        telecom, solar, UPS, and critical infrastructure.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#product-catalog" class="btn btn-accent btn-lg px-4">Browse catalog <i class="bi bi-arrow-down ms-1"></i></a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4">Ask an expert</a>
                    </div>
                    <div class="catalog-page-hero__stats">
                        <span><strong>{{ $catalogStats['products'] }}</strong><small>Products</small></span>
                        <span><strong>{{ $catalogStats['series'] }}</strong><small>Battery series</small></span>
                        <span><strong>{{ $catalogStats['applications'] }}</strong><small>Applications</small></span>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block reveal" style="transition-delay: .12s;">
                    <div class="catalog-page-hero__visual">
                        <span class="catalog-page-hero__visual-label">One trusted portfolio</span>
                        <span class="catalog-page-hero__word" aria-hidden="true">POWER</span>
                        <span class="catalog-page-hero__ring" aria-hidden="true"></span>
                        <img src="{{ asset('img/ev-7000.png') }}" alt="Rocket EV-7000 battery" class="catalog-page-hero__battery catalog-page-hero__battery--back" loading="eager">
                        <img src="{{ asset('img/ev-5200.png') }}" alt="Rocket EV-5200 battery" class="catalog-page-hero__battery catalog-page-hero__battery--front" loading="eager">
                        <div class="catalog-page-hero__proof">
                            <i class="bi bi-patch-check-fill"></i>
                            <span><strong>Rocket assured</strong><small>Performance you can depend on</small></span>
                        </div>
                        <div class="catalog-page-hero__visual-foot">
                            <span>Mobility · Backup · Renewable</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="catalog-page-hero__wave" aria-hidden="true">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,30 C250,74 470,75 730,43 C1010,9 1200,18 1440,48 L1440,80 L0,80 Z"></path>
            </svg>
        </div>
    </section>

    {{-- Live product catalog --}}
    <section id="product-catalog" class="catalog-browser">
        <span class="catalog-browser__pattern" aria-hidden="true"></span>
        <div class="container position-relative">
            <div class="catalog-browser__head reveal">
                <div>
                    <span class="section-eyebrow text-accent mb-3">Find your battery</span>
                    <h2>Choose power with confidence.</h2>
                </div>
                <p>Search by product or SKU, then narrow the range by series, application, voltage, or technical specification.</p>
            </div>

            <livewire:catalog.product-list />
        </div>
    </section>

    {{-- Product help CTA --}}
    <section class="catalog-help">
        <div class="container">
            <div class="catalog-help__shell reveal">
                <div>
                    <span class="section-eyebrow text-accent mb-3">Need help choosing?</span>
                    <h2>Tell us what you need to power.</h2>
                    <p>Our battery specialists can match capacity, cycle requirements, dimensions, and operating conditions to the right Rocket solution.</p>
                    <a href="{{ route('contact') }}" class="btn btn-accent btn-lg px-4">Talk to our team <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                <div class="catalog-help__visual d-none d-lg-flex" aria-hidden="true">
                    <span class="catalog-help__ring"></span>
                    <img src="{{ asset('img/ev-4400.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection
