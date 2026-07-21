@php
    $footerCategories = \App\Models\Category::navigationTree();
    $footerEmail = \App\Models\Setting::get('contact_email');
    $footerPhone = \App\Models\Setting::get('contact_phone');
    $footerAddress = \App\Models\Setting::get('contact_address');
    $footerWhatsapp = \App\Models\Setting::get('whatsapp_number');
    $footerSocials = [
        'facebook' => ['url' => \App\Models\Setting::get('social_facebook'), 'icon' => 'bi-facebook'],
        'linkedin' => ['url' => \App\Models\Setting::get('social_linkedin'), 'icon' => 'bi-linkedin'],
        'instagram' => ['url' => \App\Models\Setting::get('social_instagram'), 'icon' => 'bi-instagram'],
        'youtube' => ['url' => \App\Models\Setting::get('social_youtube'), 'icon' => 'bi-youtube'],
    ];
@endphp

<footer class="site-footer">
    <div class="site-footer__grid" aria-hidden="true"></div>
    <div class="site-footer__orb site-footer__orb--orange" aria-hidden="true"></div>
    <div class="site-footer__orb site-footer__orb--blue" aria-hidden="true"></div>

    <div class="container position-relative">
        <div class="footer-cta reveal">
            <div class="footer-cta__icon"><i class="bi bi-envelope-paper-heart"></i></div>
            <div class="footer-cta__copy">
                <span>Power insights, delivered</span>
                <h2>Stay charged with Rocket.</h2>
                <p>Product launches, battery guidance, and company updates—direct to your inbox.</p>
            </div>
            <div class="footer-cta__form">
                <livewire:catalog.newsletter-form />
                <small><i class="bi bi-shield-check"></i> Useful updates only. Unsubscribe anytime.</small>
            </div>
        </div>

        <div class="footer-main">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <a href="{{ url('/') }}" class="footer-brand__logo">
                            <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" height="38">
                        </a>
                        <p>Reliable energy solutions for electric mobility, telecom, solar, industrial backup, and automotive applications—engineered in India since 1952.</p>
                        <div class="footer-brand__promise">
                            <span class="footer-brand__bolt"><i class="bi bi-lightning-charge-fill"></i></span>
                            <span><strong>Unlimited Power</strong><small>Performance you can depend on</small></span>
                        </div>
                        <div class="footer-socials">
                            @foreach ($footerSocials as $name => $social)
                                @if ($social['url'])
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="Follow us on {{ ucfirst($name) }}">
                                        <i class="bi {{ $social['icon'] }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h3 class="footer-heading">Company</h3>
                    <ul class="footer-nav">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('home') }}#news">News & Events</a></li>
                        <li><a href="{{ route('home') }}#clientele">Our Clientele</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('warranty') }}">Warranty</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h3 class="footer-heading">Our Range</h3>
                    <ul class="footer-nav">
                        @foreach ($footerCategories as $category)
                            @foreach ($category->series as $series)
                                <li><a href="{{ route('series.show', $series) }}">{{ $series->name }} Series</a></li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-6 col-lg-4">
                    <h3 class="footer-heading">Let’s Talk Power</h3>
                    <div class="footer-contact">
                        @if ($footerPhone)
                            <a href="tel:{{ $footerPhone }}" class="footer-contact__item">
                                <span class="footer-contact__icon"><i class="bi bi-telephone-fill"></i></span>
                                <span><small>Call our team</small><strong>{{ $footerPhone }}</strong></span>
                            </a>
                        @endif
                        @if ($footerEmail)
                            <a href="mailto:{{ $footerEmail }}" class="footer-contact__item">
                                <span class="footer-contact__icon"><i class="bi bi-envelope-fill"></i></span>
                                <span><small>Email us</small><strong>{{ $footerEmail }}</strong></span>
                            </a>
                        @endif
                        @if ($footerAddress)
                            <div class="footer-contact__item">
                                <span class="footer-contact__icon"><i class="bi bi-geo-alt-fill"></i></span>
                                <span><small>Corporate office</small><strong>{{ $footerAddress }}</strong></span>
                            </div>
                        @endif
                    </div>
                    @if ($footerWhatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $footerWhatsapp) }}" target="_blank" rel="noopener" class="footer-whatsapp">
                            <i class="bi bi-whatsapp"></i> Start a WhatsApp conversation <i class="bi bi-arrow-up-right ms-auto"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-trust">
            @foreach ([
                ['icon' => 'bi-award-fill', 'label' => 'Established 1952'],
                ['icon' => 'bi-patch-check-fill', 'label' => 'Quality assured'],
                ['icon' => 'bi-globe2', 'label' => 'Global standards'],
                ['icon' => 'bi-headset', 'label' => 'Expert support'],
            ] as $trust)
                <span><i class="bi {{ $trust['icon'] }}"></i> {{ $trust['label'] }}</span>
            @endforeach
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ now()->year }} {{ config('app.name') }} India Private Limited. All rights reserved.</p>
            <div><span>Since 1952</span><i class="bi bi-circle-fill"></i><span>Unlimited Power</span></div>
        </div>
    </div>
</footer>
