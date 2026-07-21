@extends('layouts.app')

@section('title', 'Contact Us — ' . config('app.name'))
@section('meta_description', 'Contact Rocket Batteries India for product guidance, battery enquiries, quotations, warranty assistance, and application support.')

@section('content')
    {{-- Contact hero --}}
    <section class="contact-page-hero">
        <div class="contact-page-hero__ambient" aria-hidden="true">
            <span class="contact-page-hero__glow contact-page-hero__glow--orange"></span>
            <span class="contact-page-hero__glow contact-page-hero__glow--blue"></span>
            <span class="contact-page-hero__orbit contact-page-hero__orbit--one"></span>
            <span class="contact-page-hero__orbit contact-page-hero__orbit--two"></span>
        </div>

        <div class="container position-relative">
            <nav aria-label="breadcrumb" class="reveal">
                <ol class="breadcrumb modern-breadcrumb mb-5">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                </ol>
            </nav>

            <div class="row align-items-center g-5">
                <div class="col-lg-7 reveal">
                    <span class="hero-kicker mb-3 d-inline-flex align-items-center">
                        <i class="bi bi-chat-dots-fill"></i>
                        Talk to a battery specialist
                    </span>
                    <h1 class="contact-page-hero__title">Let’s power your<br><span>next big move.</span></h1>
                    <p class="contact-page-hero__copy">
                        Tell us about your application, capacity, or project requirements. Our team will help you
                        choose the right Rocket battery with confidence.
                    </p>
                    <a href="#send-enquiry" class="btn btn-accent btn-lg px-4">
                        Start your enquiry <i class="bi bi-arrow-down ms-1"></i>
                    </a>
                </div>

                <div class="col-lg-5 reveal" style="transition-delay: .12s;">
                    <div class="contact-page-hero__quick">
                        <div class="contact-page-hero__quick-head">
                            <span><i></i> Direct support</span>
                            <small>Choose how to connect</small>
                        </div>

                        <div class="contact-page-hero__methods">
                            @if ($settings['phone'])
                                <a href="tel:{{ $settings['phone'] }}" class="contact-page-hero__method">
                                    <span><i class="bi bi-telephone-fill"></i></span>
                                    <div><small>Call our team</small><strong>{{ $settings['phone'] }}</strong></div>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            @endif
                            @if ($settings['email'])
                                <a href="mailto:{{ $settings['email'] }}" class="contact-page-hero__method">
                                    <span><i class="bi bi-envelope-fill"></i></span>
                                    <div><small>Email enquiries</small><strong>{{ $settings['email'] }}</strong></div>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            @endif
                            @if ($settings['whatsapp'])
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" target="_blank" rel="noopener" class="contact-page-hero__method contact-page-hero__method--whatsapp">
                                    <span><i class="bi bi-whatsapp"></i></span>
                                    <div><small>Quick conversation</small><strong>Chat on WhatsApp</strong></div>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            @endif
                        </div>

                        <div class="contact-page-hero__quick-foot">
                            <span><i class="bi bi-clock-fill"></i> Fast response</span>
                            <span><i class="bi bi-person-check-fill"></i> Expert guidance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact-page-hero__wave" aria-hidden="true">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#ffffff" d="M0,30 C250,74 470,75 730,43 C1010,9 1200,18 1440,48 L1440,80 L0,80 Z"></path>
            </svg>
        </div>
    </section>

    {{-- Enquiry form and support details --}}
    <section id="send-enquiry" class="contact-showcase contact-showcase--page">
        <span class="contact-showcase__pattern" aria-hidden="true"></span>
        <div class="container position-relative">
            <div class="contact-showcase__head reveal">
                <div>
                    <span class="section-eyebrow text-accent mb-3">Start a conversation</span>
                    <h2>The right solution starts with the right questions.</h2>
                </div>
                <p>Share a few details and our team will guide you through product selection, availability, commercial requirements, and next steps.</p>
            </div>

            <div class="contact-shell reveal">
                <aside class="contact-aside">
                    <span class="contact-aside__glow" aria-hidden="true"></span>
                    <div class="contact-aside__content">
                        <span class="contact-aside__eyebrow"><i></i> Contact information</span>
                        <h3>We’re here when power matters.</h3>
                        <p>Connect with our team for product guidance, quotations, availability, and after-sales assistance.</p>

                        <div class="contact-methods">
                            @if ($settings['phone'])
                                <a href="tel:{{ $settings['phone'] }}" class="contact-method">
                                    <span class="contact-method__icon"><i class="bi bi-telephone-fill"></i></span>
                                    <span><small>Call our team</small><strong>{{ $settings['phone'] }}</strong></span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            @endif
                            @if ($settings['email'])
                                <a href="mailto:{{ $settings['email'] }}" class="contact-method">
                                    <span class="contact-method__icon"><i class="bi bi-envelope-fill"></i></span>
                                    <span><small>Email enquiries</small><strong>{{ $settings['email'] }}</strong></span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            @endif
                            @if ($settings['whatsapp'])
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" target="_blank" rel="noopener" class="contact-method contact-method--whatsapp">
                                    <span class="contact-method__icon"><i class="bi bi-whatsapp"></i></span>
                                    <span><small>Quick conversation</small><strong>Chat on WhatsApp</strong></span>
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            @endif
                            @if ($settings['address'])
                                <div class="contact-method contact-method--address">
                                    <span class="contact-method__icon"><i class="bi bi-geo-alt-fill"></i></span>
                                    <span><small>Corporate office</small><strong>{{ $settings['address'] }}</strong></span>
                                </div>
                            @endif
                        </div>

                        <div class="contact-assurances">
                            <span><i class="bi bi-clock-fill"></i><strong>Fast response</strong><small>Typically within one business day</small></span>
                            <span><i class="bi bi-person-check-fill"></i><strong>Expert guidance</strong><small>Recommendations matched to your needs</small></span>
                        </div>

                        <a href="{{ route('warranty') }}" class="contact-aside__warranty">
                            <i class="bi bi-shield-check"></i>
                            <span><small>Already own a Rocket battery?</small><strong>Register or check your warranty</strong></span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                    <img src="{{ asset('img/ev-4400.png') }}" alt="" class="contact-aside__battery" loading="lazy">
                </aside>

                <div class="contact-form-panel">
                    <div class="contact-form-panel__head">
                        <div><span>Request a consultation</span><h3>Tell us what you need.</h3></div>
                        <span class="contact-form-panel__status"><i></i> Team online</span>
                    </div>
                    <p class="contact-form-panel__intro">Complete the form below and our battery specialists will contact you with the next steps.</p>
                    <livewire:catalog.general-enquiry-form />
                    <div class="contact-form-panel__footer">
                        <span><i class="bi bi-shield-lock-fill"></i> Your information stays private</span>
                        <span><i class="bi bi-check-circle-fill"></i> No-obligation consultation</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Location --}}
    @if ($settings['mapEmbedUrl'] || $settings['address'])
        <section class="contact-location">
            <div class="container">
                <div class="contact-location__head reveal">
                    <div>
                        <span class="section-eyebrow text-accent mb-3">Find us</span>
                        <h2>Visit our corporate office.</h2>
                    </div>
                    @if ($settings['address'])
                        <p><i class="bi bi-geo-alt-fill"></i>{{ $settings['address'] }}</p>
                    @endif
                </div>

                @if ($settings['mapEmbedUrl'])
                    <div class="contact-location__map reveal">
                        <iframe src="{{ $settings['mapEmbedUrl'] }}" title="Rocket Batteries corporate office location" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        <div class="contact-location__badge">
                            <span><i class="bi bi-building-fill"></i></span>
                            <div><small>Rocket Batteries India</small><strong>Corporate Office</strong></div>
                        </div>
                    </div>
                @endif

                @if ($settings['facebook'] || $settings['linkedin'] || $settings['instagram'] || $settings['youtube'])
                    <div class="contact-social reveal">
                        <span>Follow Rocket Batteries</span>
                        <div>
                            @foreach ([
                                ['url' => $settings['facebook'], 'icon' => 'bi-facebook', 'label' => 'Facebook'],
                                ['url' => $settings['linkedin'], 'icon' => 'bi-linkedin', 'label' => 'LinkedIn'],
                                ['url' => $settings['instagram'], 'icon' => 'bi-instagram', 'label' => 'Instagram'],
                                ['url' => $settings['youtube'], 'icon' => 'bi-youtube', 'label' => 'YouTube'],
                            ] as $social)
                                @if ($social['url'])
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}"><i class="bi {{ $social['icon'] }}"></i></a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif
@endsection
