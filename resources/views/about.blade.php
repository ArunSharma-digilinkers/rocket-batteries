@extends('layouts.app')

@section('title', 'About Us — ' . config('app.name'))
@section('meta_description', 'Discover Rocket Batteries India — trusted battery engineering, dependable performance, and forward-looking energy solutions since 1952.')

@section('content')
    @php
        $yearsOfExperience = now()->year - 1952;
    @endphp

    {{-- Story-led page hero --}}
    <section class="about-page-hero">
        <div class="about-page-hero__ambient" aria-hidden="true">
            <span class="about-page-hero__glow about-page-hero__glow--orange"></span>
            <span class="about-page-hero__glow about-page-hero__glow--blue"></span>
            <span class="about-page-hero__orbit about-page-hero__orbit--one"></span>
            <span class="about-page-hero__orbit about-page-hero__orbit--two"></span>
        </div>

        <div class="container position-relative">
            <nav aria-label="breadcrumb" class="reveal">
                <ol class="breadcrumb modern-breadcrumb mb-5">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About Us</li>
                </ol>
            </nav>

            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="reveal">
                        <span class="hero-kicker mb-3 d-inline-flex align-items-center">
                            <i class="bi bi-lightning-charge-fill"></i>
                            Our story · Since 1952
                        </span>
                        <h1 class="about-page-hero__title">Energy that moves<br><span>progress forward.</span></h1>
                        <p class="about-page-hero__copy">
                            We engineer dependable battery solutions for the places that cannot afford to stop —
                            from electric mobility and telecom to solar, infrastructure, and industrial backup.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#our-story" class="btn btn-accent btn-lg px-4">
                                Discover our story <i class="bi bi-arrow-down ms-1"></i>
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg px-4">
                                Explore products
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block reveal" style="transition-delay: .12s;">
                    <div class="about-page-hero__visual">
                        <span class="about-page-hero__year" aria-hidden="true">1952</span>
                        <span class="about-page-hero__visual-label">Built on experience</span>
                        <div class="about-page-hero__product">
                            <span class="about-page-hero__product-ring" aria-hidden="true"></span>
                            <img src="{{ asset('img/ev-7000.png') }}" alt="Rocket EV-7000 battery" loading="eager">
                        </div>
                        <div class="about-page-hero__proof about-page-hero__proof--years">
                            <strong>{{ $yearsOfExperience }}+</strong>
                            <span>Years of<br>powering progress</span>
                        </div>
                        <div class="about-page-hero__proof about-page-hero__proof--quality">
                            <i class="bi bi-patch-check-fill"></i>
                            <span><strong>Quality assured</strong><small>Made for demanding conditions</small></span>
                        </div>
                        <div class="about-page-hero__visual-foot">
                            <span>Reliable by design</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="about-page-hero__wave" aria-hidden="true">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,28 C270,78 470,72 720,43 C1000,10 1210,18 1440,48 L1440,80 L0,80 Z"></path>
            </svg>
        </div>
    </section>

    {{-- Company story --}}
    <section id="our-story" class="about-story-section">
        <div class="about-story-section__pattern" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 reveal">
                    <div class="about-story-mark">
                        <div class="about-story-mark__top">
                            <span>Rocket Batteries</span>
                            <span class="about-story-mark__line"></span>
                            <span>India</span>
                        </div>
                        <div class="about-story-mark__logo">
                            <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}">
                        </div>
                        <blockquote>“Dependable power is not a promise. It is the standard we engineer into every solution.”</blockquote>
                        <div class="about-story-mark__bottom">
                            <span><i class="bi bi-geo-alt-fill"></i> Made for India</span>
                            <span><i class="bi bi-globe2"></i> Ready for the world</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="about-page-copy reveal">
                        <span class="section-eyebrow text-accent mb-3">Who we are</span>
                        <h2>Experience behind us.<br>Possibility ahead.</h2>
                        @if ($aboutContent)
                            <p class="about-page-copy__lead">{{ $aboutContent }}</p>
                        @else
                            <p class="about-page-copy__lead">
                                Rocket Batteries is a trusted energy-solutions company serving electric mobility,
                                telecom, solar, industrial backup, and automotive applications across India and beyond.
                            </p>
                        @endif

                        <div class="about-beliefs reveal-stagger">
                            @foreach ([
                                ['icon' => 'bi-cpu-fill', 'title' => 'Engineered with purpose', 'text' => 'Every product starts with a real application and the performance it demands.'],
                                ['icon' => 'bi-shield-check', 'title' => 'Reliability at the core', 'text' => 'Quality, safety, and long service life guide every decision we make.'],
                                ['icon' => 'bi-leaf-fill', 'title' => 'Progress, responsibly', 'text' => 'Smarter energy choices help build a cleaner and more efficient tomorrow.'],
                            ] as $belief)
                                <article class="about-belief">
                                    <span class="about-belief__icon"><i class="bi {{ $belief['icon'] }}"></i></span>
                                    <div>
                                        <h3>{{ $belief['title'] }}</h3>
                                        <p>{{ $belief['text'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Brand numbers --}}
    <section class="about-numbers">
        <div class="container">
            <div class="about-numbers__shell reveal">
                <div class="about-numbers__intro">
                    <span class="section-eyebrow">Rocket in numbers</span>
                    <h2>Scale built around<br>your energy needs.</h2>
                </div>
                <div class="about-numbers__grid reveal-stagger">
                    @foreach ([
                        ['label' => 'Years of experience', 'value' => $yearsOfExperience.'+', 'icon' => 'bi-hourglass-split'],
                        ['label' => 'Products', 'value' => $stats['products'], 'icon' => 'bi-battery-full'],
                        ['label' => 'Battery series', 'value' => $stats['series'], 'icon' => 'bi-collection-fill'],
                        ['label' => 'Applications served', 'value' => $stats['applications'], 'icon' => 'bi-diagram-3-fill'],
                    ] as $stat)
                        <div class="about-number">
                            <span class="about-number__icon"><i class="bi {{ $stat['icon'] }}"></i></span>
                            <strong>{{ $stat['value'] }}</strong>
                            <span>{{ $stat['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Vision and mission --}}
    <section class="about-direction">
        <div class="about-direction__glow" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row g-5 align-items-end mb-5">
                <div class="col-lg-7 reveal">
                    <span class="section-eyebrow text-accent mb-3">What drives us</span>
                    <h2 class="about-direction__title">One clear direction:<br>better energy for tomorrow.</h2>
                </div>
                <div class="col-lg-5 reveal" style="transition-delay: .08s;">
                    <p class="about-direction__intro">We combine the discipline of proven manufacturing with the ambition to keep improving how energy is stored, delivered, and used.</p>
                </div>
            </div>

            <div class="row g-4 reveal-stagger">
                <div class="col-lg-6">
                    <article class="about-direction-card h-100">
                        <div class="about-direction-card__head">
                            <span class="about-direction-card__number">01</span>
                            <span class="about-direction-card__icon"><i class="bi bi-binoculars-fill"></i></span>
                        </div>
                        <span class="about-direction-card__eyebrow">Our vision</span>
                        <h3>A trusted energy brand without boundaries.</h3>
                        <p>To become a globally recognized name for high-efficiency, reliable battery solutions across electric vehicles, energy storage, UPS, solar, and diverse application segments.</p>
                    </article>
                </div>
                <div class="col-lg-6">
                    <article class="about-direction-card about-direction-card--accent h-100">
                        <div class="about-direction-card__head">
                            <span class="about-direction-card__number">02</span>
                            <span class="about-direction-card__icon"><i class="bi bi-bullseye"></i></span>
                        </div>
                        <span class="about-direction-card__eyebrow">Our mission</span>
                        <h3>Reliable power, improved through innovation.</h3>
                        <p>To design and manufacture durable, efficient, and environmentally responsible batteries that meet evolving energy demands across mobility, infrastructure, and renewable ecosystems.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="about-cta-section">
        <div class="container">
            <div class="about-cta reveal">
                <div class="about-cta__glow" aria-hidden="true"></div>
                <div class="about-cta__content">
                    <span class="section-eyebrow text-accent mb-3">Power your next move</span>
                    <h2>Let’s build the right energy solution.</h2>
                    <p>Explore our complete range or talk to our team about the demands of your application.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg px-4">Browse catalog <i class="bi bi-arrow-right ms-1"></i></a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4">Talk to our team</a>
                    </div>
                </div>
                <div class="about-cta__product d-none d-lg-flex" aria-hidden="true">
                    <span class="about-cta__ring"></span>
                    <img src="{{ asset('img/ev-5200.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>
@endsection
