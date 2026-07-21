@extends('layouts.app')

@section('title', config('app.name') . ' — Industrial Battery Solutions')

@section('content')
    {{-- Hero / Slider --}}
    @if ($sliders->isNotEmpty())
        @php
            $heroProducts = [
                ['image' => 'img/ev-7000.png', 'model' => 'EV-7000', 'detail' => '70 Ah · High endurance'],
                ['image' => 'img/ev-5200.png', 'model' => 'EV-5200', 'detail' => '60 Ah · Daily reliability'],
                ['image' => 'img/ev-4400.png', 'model' => 'EV-4400', 'detail' => '44 Ah · Compact power'],
            ];
        @endphp

        <div
            id="heroSlider"
            class="carousel slide hero hero-grid hero-slider"
            data-bs-ride="carousel"
            data-bs-interval="5500"
            data-bs-pause="hover"
            data-bs-touch="true"
            aria-label="Featured Rocket battery solutions"
        >
            <div class="hero__ambient" aria-hidden="true">
                <div class="hero__blob hero__blob--accent"></div>
                <div class="hero__blob hero__blob--blue"></div>
                <span class="hero__orbit hero__orbit--one"></span>
                <span class="hero__orbit hero__orbit--two"></span>
            </div>

            @if ($sliders->count() > 1)
                <div class="carousel-indicators hero-slider__indicators">
                    @foreach ($sliders as $slide)
                        <button
                            type="button"
                            data-bs-target="#heroSlider"
                            data-bs-slide-to="{{ $loop->index }}"
                            class="{{ $loop->first ? 'active' : '' }}"
                            aria-label="Go to slide {{ $loop->iteration }}"
                            @if ($loop->first) aria-current="true" @endif
                        ><span></span></button>
                    @endforeach
                </div>
            @endif

            <div class="carousel-inner">
                @foreach ($sliders as $slide)
                    @php
                        $heroProduct = $heroProducts[$loop->index % count($heroProducts)];
                    @endphp
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="hero-slide text-white position-relative d-flex align-items-center">
                            <div class="container position-relative hero-slide__container">
                                <div class="row align-items-center g-5">
                                    <div class="col-lg-6">
                                        <span class="hero-animate hero-animate--1 hero-kicker mb-3 d-inline-flex align-items-center">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                            Powering progress since 1952
                                        </span>
                                        @if ($slide->title)
                                            <h1 class="hero-animate hero-animate--2 hero-title fw-bold mb-3">{{ $slide->title }}</h1>
                                        @endif
                                        @if ($slide->subtitle)
                                            <p class="hero-animate hero-animate--3 hero-copy lead mb-4">{{ $slide->subtitle }}</p>
                                        @endif
                                        <div class="hero-animate hero-animate--4 d-flex flex-wrap gap-3 mb-4">
                                            @if ($slide->cta_text && $slide->cta_url)
                                                <a href="{{ $slide->cta_url }}" class="btn btn-accent btn-lg px-4 shadow hero-primary-cta">
                                                    {{ $slide->cta_text }} <i class="bi bi-arrow-right ms-1"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4">
                                                Request a Quote
                                            </a>
                                        </div>
                                        <div class="hero-animate hero-animate--5 hero-stats d-flex flex-wrap">
                                            @foreach ([
                                                ['value' => $heroStats['years'], 'label' => 'Years Mfg'],
                                                ['value' => $heroStats['ev_series'], 'label' => 'EV Series'],
                                                ['value' => $heroStats['applications'], 'label' => 'Industries'],
                                            ] as $stat)
                                                <div class="hero-stat">
                                                    <div class="hero-stat__value">{{ $stat['value'] }}</div>
                                                    <div class="hero-stat__label">{{ $stat['label'] }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-lg-6 d-none d-lg-block text-center">
                                        <div class="hero-animate hero-animate--visual hero-product-wrap position-relative d-inline-block">
                                            <span class="hero-product-badge hero-float-card hero-float-card--top">
                                                <i class="bi bi-patch-check-fill"></i>
                                                Quality assured
                                            </span>
                                            <div class="hero-product-frame">
                                                <div class="hero-product-frame__glow"></div>
                                                <img src="{{ asset($heroProduct['image']) }}" alt="Rocket {{ $heroProduct['model'] }} battery" class="hero-product-image">
                                                <div class="hero-product-frame__caption">
                                                    <div>
                                                        <span>Featured model</span>
                                                        <strong>{{ $heroProduct['model'] }}</strong>
                                                    </div>
                                                    <div class="hero-product-frame__detail">{{ $heroProduct['detail'] }}</div>
                                                </div>
                                            </div>
                                            <span class="hero-float-card hero-float-card--bottom">
                                                <i class="bi bi-shield-check"></i>
                                                <span><strong>Built to last</strong><small>Safe. Reliable. Efficient.</small></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous slide</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next slide</span>
                </button>
            @endif

            <div class="hero-slider__status" aria-live="polite">
                <span class="hero-slider__pause"><i class="bi bi-pause-fill"></i> Paused</span>
            </div>

            <div class="hero-wave">
                <svg viewBox="0 0 1440 80" preserveAspectRatio="none" style="width: 100%; height: 60px; display: block;">
                    <path fill="#ffffff" d="M0,32 C240,80 480,80 720,48 C960,16 1200,16 1440,48 L1440,80 L0,80 Z"></path>
                </svg>
            </div>
        </div>
    @endif

    {{-- Certifications --}}
    <section class="bg-light py-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center gap-3 gap-md-4 text-center">
                <span class="section-eyebrow text-muted" style="font-size: .7rem;">Certified to Global Standards</span>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach (['ISO 9001:2015', 'IEC 60896', 'BIS Certified', 'RoHS Compliant'] as $certification)
                        <span class="badge bg-white border text-body fw-semibold px-3 py-2 rounded-pill">{{ $certification }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- About + Vision/Mission --}}
    <section id="about" class="about-showcase overflow-hidden">
        <div class="about-showcase__pattern" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 reveal">
                    <div class="about-visual">
                        <span class="about-visual__year" aria-hidden="true">1952</span>
                        <div class="about-visual__head">
                            <span>Our heritage</span><span class="about-visual__line"></span><span>Our future</span>
                        </div>
                        <div class="about-visual__product">
                            <span class="about-visual__halo" aria-hidden="true"></span>
                            <img src="{{ asset('img/ev-5200.png') }}" alt="Rocket EV-5200 industrial battery" loading="lazy">
                        </div>
                        <div class="about-fact about-fact--experience">
                            <strong>{{ $heroStats['years'] }}</strong><span>Years of<br>manufacturing</span>
                        </div>
                        <div class="about-fact about-fact--quality">
                            <i class="bi bi-patch-check-fill"></i>
                            <span><strong>Proven quality</strong><small>Engineered for real-world demands</small></span>
                        </div>
                        <div class="about-visual__footer">
                            <span><i class="bi bi-geo-alt-fill"></i> Made for India</span>
                            <span><i class="bi bi-globe2"></i> Built for the world</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="about-content reveal">
                        <span class="section-eyebrow text-accent mb-3 d-inline-flex">Who We Are</span>
                        <h2 class="about-title fw-bold mb-4">Powering ambition with energy solutions that endure.</h2>
                        <p class="about-lead mb-4">
                            Rocket Batteries delivers dependable power across electric mobility, telecom, solar,
                            industrial backup, and automotive applications. Our engineering experience and
                            future-focused approach help businesses move further with confidence.
                        </p>
                        <div class="about-pillars reveal-stagger mb-4">
                            @foreach ([
                                ['icon' => 'bi-cpu-fill', 'title' => 'Advanced engineering', 'text' => 'Purpose-built technology for demanding applications.'],
                                ['icon' => 'bi-shield-check', 'title' => 'Dependable quality', 'text' => 'Consistent power, safety, and long service life.'],
                                ['icon' => 'bi-leaf-fill', 'title' => 'Future conscious', 'text' => 'Smarter energy choices for a more efficient tomorrow.'],
                            ] as $pillar)
                                <div class="about-pillar">
                                    <span class="about-pillar__icon"><i class="bi {{ $pillar['icon'] }}"></i></span>
                                    <div><h3>{{ $pillar['title'] }}</h3><p>{{ $pillar['text'] }}</p></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row g-3 reveal-stagger">
                            <div class="col-md-6">
                                <article class="about-purpose-card about-purpose-card--vision h-100">
                                    <div class="about-purpose-card__top"><span class="about-purpose-card__number">01</span><span class="about-purpose-card__icon"><i class="bi bi-binoculars-fill"></i></span></div>
                                    <h3>Our Vision</h3>
                                    <p>To become a globally trusted brand for efficient, reliable energy solutions across mobility, backup, and renewable power.</p>
                                </article>
                            </div>
                            <div class="col-md-6">
                                <article class="about-purpose-card about-purpose-card--mission h-100">
                                    <div class="about-purpose-card__top"><span class="about-purpose-card__number">02</span><span class="about-purpose-card__icon"><i class="bi bi-bullseye"></i></span></div>
                                    <h3>Our Mission</h3>
                                    <p>To advance battery performance through quality, innovation, and responsible engineering for a sustainable future.</p>
                                </article>
                            </div>
                        </div>
                        <a href="{{ route('about') }}" class="about-story-link mt-4">
                            Discover the Rocket story <span><i class="bi bi-arrow-up-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Key Features --}}
    <section class="features-showcase">
        <div class="features-showcase__glow features-showcase__glow--one" aria-hidden="true"></div>
        <div class="features-showcase__glow features-showcase__glow--two" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row g-5 align-items-center">
                <div class="col-lg-4 reveal">
                    <span class="section-eyebrow text-accent mb-3">Why Rocket EV</span>
                    <h2 class="features-showcase__title">Performance engineered into every detail.</h2>
                    <p class="features-showcase__intro">From the first charge to the thousandth journey, every Rocket EV battery is designed to deliver dependable power without compromise.</p>
                    <div class="features-showcase__product">
                        <span class="features-showcase__ring" aria-hidden="true"></span>
                        <img src="{{ asset('img/ev-3200.png') }}" alt="Rocket EV battery" loading="lazy">
                        <span class="features-showcase__badge"><i class="bi bi-lightning-charge-fill"></i> EV engineered</span>
                    </div>
                    <a href="{{ route('products.index') }}" class="features-showcase__link">
                        Explore our EV range <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3 reveal-stagger">
                @foreach ([
                    ['icon' => 'bi-lightning-charge-fill', 'title' => 'High Energy Efficiency', 'text' => 'Maximum usable power, longer driving range, and reduced energy loss.', 'tag' => 'More range'],
                    ['icon' => 'bi-shield-fill-check', 'title' => 'Advanced Safety', 'text' => 'Thermal protection and intelligent monitoring across every cycle.', 'tag' => 'Protected'],
                    ['icon' => 'bi-arrow-repeat', 'title' => 'Long Cycle Life', 'text' => 'Durable chemistry that lowers lifetime ownership and replacement costs.', 'tag' => 'Built longer'],
                    ['icon' => 'bi-plug-fill', 'title' => 'Fast Charging', 'text' => 'Rapid charging performance without compromising long-term battery health.', 'tag' => 'Less waiting'],
                    ['icon' => 'bi-grid-3x3-gap-fill', 'title' => 'Wide Compatibility', 'text' => 'Engineered for e-rickshaws, two-wheelers, three-wheelers, and LCVs.', 'tag' => 'One platform'],
                    ['icon' => 'bi-thermometer-half', 'title' => 'All-Weather Output', 'text' => 'Consistent delivery across changing temperatures and demanding roads.', 'tag' => 'Always ready'],
                ] as $feature)
                    <div class="col-sm-6">
                        <article class="feature-card h-100">
                            <div class="feature-card__top">
                                <span class="feature-card__icon"><i class="bi {{ $feature['icon'] }}"></i></span>
                                <span class="feature-card__number">{{ sprintf('%02d', $loop->iteration) }}</span>
                            </div>
                            <span class="feature-card__tag">{{ $feature['tag'] }}</span>
                            <h3>{{ $feature['title'] }}</h3>
                            <p>{{ $feature['text'] }}</p>
                            <span class="feature-card__arrow"><i class="bi bi-arrow-up-right"></i></span>
                        </article>
                    </div>
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- EV Products spec switcher --}}
    <section id="ev-products" class="ev-catalog">
        <div class="container position-relative">
            <div class="text-center mb-5 reveal">
                <div class="section-eyebrow text-accent mb-2">Products Overview — EV</div>
                <h2 class="ev-catalog__heading">Meet the power behind every journey.</h2>
                <p class="ev-catalog__intro">
                    Four purpose-built batteries. One standard of dependable performance for modern electric mobility.
                </p>
            </div>

            <div x-data="{ active: 'EV-5200' }" class="ev-catalog__shell reveal">
                <div class="ev-catalog__tabs" role="tablist" aria-label="Rocket EV battery models">
                    @foreach ($evSpecs as $sku => $spec)
                        <button
                            type="button"
                            class="ev-model-tab"
                            :class="active === '{{ $sku }}' ? 'is-active' : ''"
                            @click="active = '{{ $sku }}'"
                            :aria-selected="(active === '{{ $sku }}').toString()"
                            role="tab"
                        >
                            <span class="ev-model-tab__image"><img src="{{ asset($spec['image']) }}" alt="" loading="lazy"></span>
                            <span><small>Rocket EV</small><strong>{{ $sku }}</strong></span>
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    @endforeach
                </div>

                <div class="ev-catalog__stage">
                    @foreach ($evSpecs as $sku => $spec)
                        <div x-show="active === '{{ $sku }}'" x-transition.opacity.duration.350ms x-cloak class="ev-product-panel" role="tabpanel">
                            <div class="row g-0 align-items-center">
                                <div class="col-lg-5">
                                    <div class="ev-product-visual">
                                        <span class="ev-product-visual__model">{{ str_replace('EV-', '', $sku) }}</span>
                                        <span class="ev-product-visual__orbit" aria-hidden="true"></span>
                                        <img src="{{ asset($spec['image']) }}" alt="Rocket {{ $sku }} battery">
                                        <span class="ev-product-visual__seal"><i class="bi bi-patch-check-fill"></i> Genuine Rocket</span>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="ev-product-content">
                                        <span class="ev-product-content__eyebrow">Electric mobility series</span>
                                        <h3>Rocket {{ $sku }}</h3>
                                        <p>Sealed, maintenance-free power engineered for reliable daily mobility, consistent output, and longer operating life.</p>
                                        <div class="ev-spec-grid">
                                        @foreach ([
                                            ['icon' => 'bi-lightning-charge-fill', 'label' => 'Capacity C5', 'value' => $spec['c5']],
                                            ['icon' => 'bi-lightning-fill', 'label' => 'Capacity C20', 'value' => $spec['c20']],
                                            ['icon' => 'bi-bounding-box', 'label' => 'Dimensions', 'value' => $spec['dimensions']],
                                            ['icon' => 'bi-box-seam-fill', 'label' => 'Weight', 'value' => $spec['weight']],
                                        ] as $row)
                                            <div class="ev-spec">
                                                <span><i class="bi {{ $row['icon'] }}"></i></span>
                                                <div><small>{{ $row['label'] }}</small><strong>{{ $row['value'] }}</strong></div>
                                            </div>
                                        @endforeach
                                        </div>
                                        <div class="ev-product-actions">
                                            <a href="{{ route('home') }}#contact-quote" class="btn btn-accent px-4">Request a Quote <i class="bi bi-arrow-right ms-1"></i></a>
                                            <span><i class="bi bi-shield-check"></i> Safe &amp; maintenance-free</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Other series (EnerRocket) --}}
    @if ($otherSeries->isNotEmpty())
        <section id="other-series" class="series-showcase">
            <div class="container position-relative">
                <div class="row align-items-end mb-5 reveal">
                    <div class="col-lg-7">
                    <div class="section-eyebrow text-accent mb-2">Product Overview — EnerRocket</div>
                        <h2 class="series-showcase__heading">Power solutions beyond the road.</h2>
                    </div>
                    <div class="col-lg-5"><p class="series-showcase__intro">Dependable stationary energy for UPS, solar, telecom, and demanding backup applications.</p></div>
                </div>
                <div class="row g-4 reveal-stagger">
                    @foreach ($otherSeries as $series)
                        @php
                            $seriesImage = ['img/ev-7000.png', 'img/ev-5200.png', 'img/ev-4400.png'][$loop->index % 3];
                        @endphp
                        <div class="col-md-4">
                            <a href="{{ route('series.show', $series) }}" class="series-card h-100">
                                <div class="series-card__visual">
                                    <span class="series-card__number">{{ sprintf('%02d', $loop->iteration) }}</span>
                                    <span class="series-card__ring" aria-hidden="true"></span>
                                    <img src="{{ asset($seriesImage) }}" alt="{{ $series->name }} battery series" loading="lazy">
                                    <span class="series-card__type">Stationary power</span>
                                </div>
                                <div class="series-card__body">
                                    <h3>{{ $series->name }} Series</h3>
                                    <p>{{ $series->description ?: 'Reliable backup performance engineered for critical power applications.' }}</p>
                                    <span>View series <i class="bi bi-arrow-up-right"></i></span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- News & Events --}}
    @if ($newsEvents->isNotEmpty())
        <section id="news" class="py-5">
            <div class="container py-4">
                <div class="text-center mb-5 reveal">
                    <div class="section-eyebrow text-accent mb-2">News & Events</div>
                    <h2 class="fw-bold">Innovation, events, industry engagement.</h2>
                    <p class="text-muted mx-auto" style="max-width: 620px;">
                        Explore our track record demonstrating our commitment to excellence and market leadership.
                    </p>
                </div>
                <div class="row g-4 reveal-stagger">
                    @foreach ($newsEvents as $event)
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="placeholder-media rounded-top" style="height: 200px;">
                                    <i class="bi bi-calendar-event display-3"></i>
                                </div>
                                <div class="card-body">
                                    @if ($event->event_date)
                                        <div class="section-eyebrow text-accent mb-1">{{ $event->event_date->format('d M Y') }}</div>
                                    @endif
                                    <h3 class="h5 fw-bold">{{ $event->title }}</h3>
                                    <p class="text-muted small mb-0">{{ $event->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Applications --}}
    @if ($applications->isNotEmpty())
        <section class="applications-showcase">
            <div class="container position-relative">
                @php
                    $industryWords = [1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten'];
                    $industryCount = $applications->count();
                @endphp
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 reveal">
                        <div class="section-eyebrow text-accent mb-3">Built for Every Demand</div>
                        <h2 class="applications-showcase__heading">Powering {{ $industryWords[$industryCount] ?? $industryCount }} {{ \Illuminate\Support\Str::plural('industry', $industryCount) }} with confidence.</h2>
                        <p class="applications-showcase__intro">One trusted battery partner across mobility, infrastructure, renewable energy, and mission-critical systems.</p>
                        <div class="applications-grid reveal-stagger">
                            @foreach ($applications as $application)
                                <article class="application-card">
                                    <span><i class="bi {{ $application->icon_class }}"></i></span>
                                    <div><h3>{{ $application->name }}</h3><p>{{ $application->description }}</p></div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6 reveal">
                        <div class="battery-lineup">
                            <div class="battery-lineup__head">
                                <div>
                                    <span>Rocket EV Series</span>
                                    <h3>One range. Every journey.</h3>
                                </div>
                                <span class="battery-lineup__count">04 models</span>
                            </div>
                            <div class="battery-lineup__products">
                                @foreach ([
                                    ['image' => 'img/ev-3200.png', 'model' => 'EV-3200G', 'capacity' => '32 Ah'],
                                    ['image' => 'img/ev-4400.png', 'model' => 'EV-4400', 'capacity' => '44 Ah'],
                                    ['image' => 'img/ev-5200.png', 'model' => 'EV-5200', 'capacity' => '60 Ah'],
                                    ['image' => 'img/ev-7000.png', 'model' => 'EV-7000', 'capacity' => '70 Ah'],
                                ] as $battery)
                                    <figure class="battery-lineup__product">
                                        <div><img src="{{ asset($battery['image']) }}" alt="Rocket {{ $battery['model'] }} battery" loading="lazy"></div>
                                        <figcaption><strong>{{ $battery['model'] }}</strong><span>{{ $battery['capacity'] }}</span></figcaption>
                                    </figure>
                                @endforeach
                            </div>
                            <div class="battery-lineup__footer">
                                <span><i class="bi bi-shield-check"></i> Maintenance-free</span>
                                <span><i class="bi bi-lightning-charge"></i> 30–70 Ah range</span>
                                <a href="{{ route('home') }}#ev-products">Compare models <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Trusted by / Clientele --}}
    @if ($clients->isNotEmpty())
        <section id="clientele" class="hero py-5">
            <div class="container py-4 position-relative">
                <div class="text-center mb-5 reveal">
                    <div class="section-eyebrow text-accent">Trusted By Industry Leaders</div>
                </div>
                <div class="row g-3 align-items-center justify-content-center reveal-stagger">
                    @foreach ($clients as $client)
                        <div class="col-4 col-md-2 text-center">
                            <div class="bg-white rounded-3 p-3 d-flex align-items-center justify-content-center hover-lift" style="height: 90px;">
                                <img src="{{ asset($client->logo) }}" alt="{{ $client->name }}" class="img-fluid client-logo" style="max-height: 48px;" loading="lazy">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        @php
            $averageRating = number_format($testimonials->avg('rating'), 1);
        @endphp
        <section class="testimonial-showcase">
            <span class="testimonial-showcase__pattern" aria-hidden="true"></span>
            <div class="container position-relative">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-4 reveal">
                        <span class="section-eyebrow text-accent mb-3">Customer Stories</span>
                        <h2 class="testimonial-showcase__heading">Trusted power. Proven in the real world.</h2>
                        <p class="testimonial-showcase__intro">Hear from the people who depend on Rocket Batteries to keep their operations, infrastructure, and mobility moving.</p>

                        <div class="testimonial-trust">
                            <div class="testimonial-trust__score">
                                <strong>{{ $averageRating }}</strong>
                                <div>
                                    <span>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= round($testimonials->avg('rating')) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </span>
                                    <small>Average customer rating</small>
                                </div>
                            </div>
                            <div class="testimonial-trust__line"></div>
                            <div class="testimonial-trust__promise"><i class="bi bi-patch-check-fill"></i><span><strong>Built on reliability</strong><small>Service, quality, and consistent performance</small></span></div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="testimonial-carousel-wrap reveal">
                            <span class="testimonial-carousel-wrap__quote" aria-hidden="true">“</span>
                            <div id="testimonialsCarousel" class="carousel slide testimonial-carousel" data-bs-ride="carousel" data-bs-interval="7000" data-bs-pause="hover">
                                <div class="carousel-inner">
                            @foreach ($testimonials as $testimonial)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <article class="testimonial-card">
                                        <div class="testimonial-card__top">
                                            <span>Verified customer</span>
                                            @if ($testimonial->rating)
                                                <div class="testimonial-card__stars" aria-label="{{ $testimonial->rating }} out of 5 stars">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $testimonial->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            @endif
                                        </div>
                                        <blockquote>{{ $testimonial->content }}</blockquote>
                                        <div class="testimonial-card__author">
                                            <span class="testimonial-card__avatar">{{ strtoupper(mb_substr($testimonial->name, 0, 1)) }}</span>
                                            <div><strong>{{ $testimonial->name }}</strong><small>{{ $testimonial->designation }}</small></div>
                                            @if ($testimonial->company)
                                                <span class="testimonial-card__company"><i class="bi bi-buildings"></i> {{ $testimonial->company }}</span>
                                            @endif
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                                </div>

                                @if ($testimonials->count() > 1)
                                    <div class="testimonial-carousel__nav">
                                        <div class="testimonial-carousel__indicators">
                                            @foreach ($testimonials as $testimonial)
                                                <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Show testimonial {{ $loop->iteration }}"></button>
                                            @endforeach
                                        </div>
                                        <div class="testimonial-carousel__arrows">
                                            <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev" aria-label="Previous testimonial"><i class="bi bi-arrow-left"></i></button>
                                            <button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next" aria-label="Next testimonial"><i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="project-cta-section">
        <div class="container">
            <div class="project-cta reveal">
                <span class="project-cta__grid" aria-hidden="true"></span>
                <div class="row g-0 align-items-center">
                    <div class="col-lg-7">
                        <div class="project-cta__content">
                        <span class="section-eyebrow text-accent mb-3 d-inline-flex">Let’s Build Together</span>
                        <h2>Ready to power your next project?</h2>
                        <p>
                            From EV mobility to industrial backup — explore our full range or talk to our
                            team for a tailored recommendation.
                        </p>
                        <div class="project-cta__actions">
                            <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg px-4 main-nav-cta">
                                Browse Catalog <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <a href="{{ route('home') }}#contact-quote" class="btn btn-outline-light btn-lg px-4">
                                Get a Free Quote
                            </a>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block">
                        <div class="project-cta__visual">
                            <span class="project-cta__halo" aria-hidden="true"></span>
                            <img src="{{ asset('img/ev-7000.png') }}" alt="Rocket EV-7000 battery" class="project-cta__battery project-cta__battery--back" loading="lazy">
                            <img src="{{ asset('img/ev-5200.png') }}" alt="Rocket EV-5200 battery" class="project-cta__battery project-cta__battery--front" loading="lazy">
                            <span class="project-cta__badge"><i class="bi bi-patch-check-fill"></i><strong>Rocket assured</strong><small>Power that performs</small></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact / Quote --}}
    <section id="contact-quote" class="contact-showcase">
        <span class="contact-showcase__pattern" aria-hidden="true"></span>
        <div class="container position-relative">
            @php
                $quoteWhatsapp = \App\Models\Setting::get('whatsapp_number');
                $quotePhone = \App\Models\Setting::get('contact_phone');
                $quoteEmail = \App\Models\Setting::get('contact_email');
            @endphp
            <div class="contact-showcase__head reveal">
                <div>
                    <span class="section-eyebrow text-accent mb-3">Start a Conversation</span>
                    <h2>Let’s find the right power solution.</h2>
                </div>
                <p>Share your application, capacity, or project requirements. Our battery specialists will help you choose with confidence.</p>
            </div>

            <div class="contact-shell reveal">
                <aside class="contact-aside">
                    <span class="contact-aside__glow" aria-hidden="true"></span>
                    <div class="contact-aside__content">
                        <span class="contact-aside__eyebrow"><i></i> Direct expert support</span>
                        <h3>Prefer to talk first?</h3>
                        <p>Connect directly with our team for product guidance, availability, and commercial enquiries.</p>

                        <div class="contact-methods">
                        @if ($quotePhone)
                                <a href="tel:{{ $quotePhone }}" class="contact-method">
                                    <span class="contact-method__icon"><i class="bi bi-telephone-fill"></i></span>
                                    <span><small>Call our team</small><strong>{{ $quotePhone }}</strong></span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                        @endif
                        @if ($quoteWhatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $quoteWhatsapp) }}" target="_blank" rel="noopener" class="contact-method contact-method--whatsapp">
                                    <span class="contact-method__icon"><i class="bi bi-whatsapp"></i></span>
                                    <span><small>Quick conversation</small><strong>Chat on WhatsApp</strong></span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                        @endif
                        @if ($quoteEmail)
                                <a href="mailto:{{ $quoteEmail }}" class="contact-method">
                                    <span class="contact-method__icon"><i class="bi bi-envelope-fill"></i></span>
                                    <span><small>Email enquiries</small><strong>{{ $quoteEmail }}</strong></span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                        @endif
                        </div>

                        <div class="contact-assurances">
                            <span><i class="bi bi-clock-fill"></i><strong>Fast response</strong><small>Typically within one business day</small></span>
                            <span><i class="bi bi-person-check-fill"></i><strong>Expert guidance</strong><small>Recommendations matched to your need</small></span>
                        </div>
                    </div>
                    <img src="{{ asset('img/ev-4400.png') }}" alt="Rocket EV-4400 battery" class="contact-aside__battery" loading="lazy">
                </aside>

                <div class="contact-form-panel">
                    <div class="contact-form-panel__head">
                        <div><span>Request a consultation</span><h3>Tell us what you need.</h3></div>
                        <span class="contact-form-panel__status"><i></i> Team online</span>
                    </div>
                    <p class="contact-form-panel__intro">Complete the form below and our team will contact you with the next steps.</p>
                    <livewire:catalog.general-enquiry-form />
                    <div class="contact-form-panel__footer">
                        <span><i class="bi bi-shield-lock-fill"></i> Your information stays private</span>
                        <span><i class="bi bi-check-circle-fill"></i> No-obligation consultation</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
