@extends('layouts.app')

@section('title', 'Warranty Registration & Lookup — ' . config('app.name'))
@section('meta_description', 'Register your Rocket Batteries product warranty or securely check the status of an existing registration.')

@section('content')
    {{-- Warranty hero --}}
    <section class="warranty-page-hero">
        <div class="warranty-page-hero__ambient" aria-hidden="true">
            <span class="warranty-page-hero__glow warranty-page-hero__glow--orange"></span>
            <span class="warranty-page-hero__glow warranty-page-hero__glow--blue"></span>
            <span class="warranty-page-hero__orbit warranty-page-hero__orbit--one"></span>
            <span class="warranty-page-hero__orbit warranty-page-hero__orbit--two"></span>
        </div>

        <div class="container position-relative">
            <nav aria-label="breadcrumb" class="reveal">
                <ol class="breadcrumb modern-breadcrumb mb-5">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Warranty</li>
                </ol>
            </nav>

            <div class="row align-items-center g-5">
                <div class="col-lg-7 reveal">
                    <span class="hero-kicker mb-3 d-inline-flex align-items-center">
                        <i class="bi bi-patch-check-fill"></i>
                        Rocket ownership support
                    </span>
                    <h1 class="warranty-page-hero__title">Power protected.<br><span>Confidence included.</span></h1>
                    <p class="warranty-page-hero__copy">
                        Register your Rocket battery in a few simple steps, keep your purchase details secure,
                        and check your verification status whenever you need it.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#warranty-service" class="btn btn-accent btn-lg px-4">Register now <i class="bi bi-arrow-down ms-1"></i></a>
                        <a href="#warranty-service" class="btn btn-outline-light btn-lg px-4">Check status</a>
                    </div>
                    <div class="warranty-page-hero__benefits">
                        <span><i class="bi bi-shield-lock-fill"></i> Secure registration</span>
                        <span><i class="bi bi-clock-history"></i> Easy status tracking</span>
                        <span><i class="bi bi-headset"></i> Dedicated support</span>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block reveal" style="transition-delay: .12s;">
                    <div class="warranty-page-hero__visual">
                        <span class="warranty-page-hero__visual-label">Rocket assured</span>
                        <div class="warranty-page-hero__shield" aria-hidden="true"><i class="bi bi-shield-check"></i></div>
                        <div class="warranty-page-hero__product">
                            <span class="warranty-page-hero__ring" aria-hidden="true"></span>
                            <img src="{{ asset('img/ev-5200.png') }}" alt="Rocket EV-5200 battery" loading="eager">
                        </div>
                        <div class="warranty-page-hero__proof warranty-page-hero__proof--verified">
                            <i class="bi bi-patch-check-fill"></i>
                            <span><strong>Official coverage</strong><small>Direct from Rocket Batteries</small></span>
                        </div>
                        <div class="warranty-page-hero__proof warranty-page-hero__proof--simple">
                            <strong>03</strong><span>Simple<br>steps</span>
                        </div>
                        <div class="warranty-page-hero__visual-foot">
                            <span>Your battery. Your protection.</span>
                            <i class="bi bi-arrow-up-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="warranty-page-hero__wave" aria-hidden="true">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,30 C250,74 470,75 730,43 C1010,9 1200,18 1440,48 L1440,80 L0,80 Z"></path>
            </svg>
        </div>
    </section>

    {{-- Registration and lookup workspace --}}
    <section id="warranty-service" class="warranty-service" x-data="{ tab: 'register' }">
        <span class="warranty-service__pattern" aria-hidden="true"></span>
        <div class="container position-relative">
            <div class="warranty-service__head reveal">
                <div>
                    <span class="section-eyebrow text-accent mb-3">Warranty centre</span>
                    <h2>Manage your coverage in one place.</h2>
                </div>
                <p>New purchase? Register your battery. Already submitted? Use your serial number and mobile number to securely check its status.</p>
            </div>

            <div class="warranty-workspace reveal">
                <aside class="warranty-guide">
                    <span class="warranty-guide__glow" aria-hidden="true"></span>
                    <div class="warranty-guide__content">
                        <span class="warranty-guide__eyebrow"><i></i> Simple and secure</span>
                        <h3>Protection in three easy steps.</h3>
                        <p>Keep your battery details and proof of purchase ready. Registration only takes a few minutes.</p>

                        <div class="warranty-steps">
                            @foreach ([
                                ['icon' => 'bi-person-fill', 'title' => 'Add owner details', 'text' => 'Tell us who owns the battery.'],
                                ['icon' => 'bi-upc-scan', 'title' => 'Identify your battery', 'text' => 'Select the product and enter its serial number.'],
                                ['icon' => 'bi-receipt', 'title' => 'Confirm your purchase', 'text' => 'Add the date and optional invoice copy.'],
                            ] as $step)
                                <div class="warranty-step">
                                    <span class="warranty-step__number">{{ sprintf('%02d', $loop->iteration) }}</span>
                                    <span class="warranty-step__icon"><i class="bi {{ $step['icon'] }}"></i></span>
                                    <div><strong>{{ $step['title'] }}</strong><small>{{ $step['text'] }}</small></div>
                                </div>
                            @endforeach
                        </div>

                        <div class="warranty-guide__note">
                            <i class="bi bi-info-circle-fill"></i>
                            <span><strong>Where is my serial number?</strong><small>Look for the printed or engraved identification code on your battery label.</small></span>
                        </div>
                    </div>
                    <img src="{{ asset('img/ev-3200.png') }}" alt="" class="warranty-guide__battery" loading="lazy">
                </aside>

                <div class="warranty-panel">
                    <div class="warranty-tabs" role="tablist" aria-label="Warranty services">
                        <button type="button" role="tab" :aria-selected="(tab === 'register').toString()" :class="tab === 'register' ? 'is-active' : ''" @click="tab = 'register'">
                            <span><i class="bi bi-shield-plus"></i></span>
                            <div><small>New purchase</small><strong>Register warranty</strong></div>
                        </button>
                        <button type="button" role="tab" :aria-selected="(tab === 'lookup').toString()" :class="tab === 'lookup' ? 'is-active' : ''" @click="tab = 'lookup'">
                            <span><i class="bi bi-search"></i></span>
                            <div><small>Already registered</small><strong>Check status</strong></div>
                        </button>
                    </div>

                    <div x-show="tab === 'register'" x-cloak class="warranty-panel__body">
                        <div class="warranty-panel__head">
                            <div><span>Warranty registration</span><h3>Register your Rocket battery.</h3></div>
                            <span class="warranty-panel__secure"><i class="bi bi-lock-fill"></i> Secure</span>
                        </div>
                        <p class="warranty-panel__intro">Fields marked with an asterisk are required. Your information is used only to verify and support your warranty.</p>
                        <livewire:catalog.warranty-registration-form />
                    </div>

                    <div x-show="tab === 'lookup'" x-cloak class="warranty-panel__body">
                        <div class="warranty-panel__head">
                            <div><span>Warranty lookup</span><h3>Check your registration status.</h3></div>
                            <span class="warranty-panel__secure"><i class="bi bi-lock-fill"></i> Secure</span>
                        </div>
                        <p class="warranty-panel__intro">Enter the same serial number and mobile number used during registration.</p>
                        <livewire:catalog.warranty-lookup-form />
                    </div>
                </div>
            </div>

            <div class="warranty-help reveal-stagger">
                <article>
                    <span><i class="bi bi-file-earmark-check-fill"></i></span>
                    <div><h3>Keep proof of purchase</h3><p>An invoice helps our team complete verification quickly.</p></div>
                </article>
                <article>
                    <span><i class="bi bi-phone-fill"></i></span>
                    <div><h3>Use an active number</h3><p>Your mobile number is required for secure status lookup.</p></div>
                </article>
                <article>
                    <span><i class="bi bi-headset"></i></span>
                    <div><h3>Need assistance?</h3><p><a href="{{ route('contact') }}">Contact our team</a> for registration or product support.</p></div>
                </article>
            </div>
        </div>
    </section>
@endsection
