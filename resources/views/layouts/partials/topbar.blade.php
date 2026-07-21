@php
    $topbarPhone = \App\Models\Setting::get('contact_phone');
    $topbarEmail = \App\Models\Setting::get('contact_email');
    $topbarSocials = [
        ['name' => 'Facebook', 'url' => \App\Models\Setting::get('social_facebook'), 'icon' => 'bi-facebook'],
        ['name' => 'LinkedIn', 'url' => \App\Models\Setting::get('social_linkedin'), 'icon' => 'bi-linkedin'],
        ['name' => 'Instagram', 'url' => \App\Models\Setting::get('social_instagram'), 'icon' => 'bi-instagram'],
        ['name' => 'YouTube', 'url' => \App\Models\Setting::get('social_youtube'), 'icon' => 'bi-youtube'],
    ];
    $hasSocials = collect($topbarSocials)->contains(fn ($social) => $social['url']);
@endphp

@if ($topbarPhone || $topbarEmail || $hasSocials)
    <div class="site-topbar d-none d-md-block">
        <div class="container site-topbar__inner">
            <div class="site-topbar__left">
                <span class="site-topbar__promise">
                    <i></i> Engineering unlimited power since 1952
                </span>

                @if ($topbarPhone || $topbarEmail)
                    <span class="site-topbar__divider d-none d-lg-inline"></span>
                @endif

                @if ($topbarPhone)
                    <a href="tel:{{ $topbarPhone }}" class="site-topbar__contact d-none d-lg-flex">
                        <span><i class="bi bi-telephone-fill"></i></span> {{ $topbarPhone }}
                    </a>
                @endif
                @if ($topbarEmail)
                    <a href="mailto:{{ $topbarEmail }}" class="site-topbar__contact d-none d-xl-flex">
                        <span><i class="bi bi-envelope-fill"></i></span> {{ $topbarEmail }}
                    </a>
                @endif
            </div>

            <div class="site-topbar__right">
                <a href="{{ route('warranty') }}" class="site-topbar__utility"><i class="bi bi-shield-check"></i> Warranty support</a>
                @if ($hasSocials)
                    <span class="site-topbar__divider"></span>
                    @foreach ($topbarSocials as $social)
                        @if ($social['url'])
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="site-topbar__social" aria-label="Follow Rocket Batteries on {{ $social['name'] }}">
                                <i class="bi {{ $social['icon'] }}"></i>
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endif
