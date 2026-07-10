@php
    $pageTitle = trim($__env->yieldContent('title')) ?: config('app.name');
    $pageDescription = trim($__env->yieldContent('meta_description')) ?: 'Rocket Batteries India — industrial batteries for UPS, solar, telecom, EV and more.';
    $ogImage = trim($__env->yieldContent('og_image'));
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<link rel="canonical" href="{{ url()->current() }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ url()->current() }}">
@if ($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif

<meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
@if ($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}">
@endif

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => config('app.name'),
    'url' => url('/'),
    'email' => \App\Models\Setting::get('contact_email'),
    'telephone' => \App\Models\Setting::get('contact_phone'),
    'sameAs' => array_values(array_filter([
        \App\Models\Setting::get('social_facebook'),
        \App\Models\Setting::get('social_linkedin'),
        \App\Models\Setting::get('social_instagram'),
    ])),
], JSON_UNESCAPED_SLASHES) !!}
</script>

@yield('structured_data')

@vite(['resources/sass/app.scss', 'resources/js/app.js'])
@livewireStyles
