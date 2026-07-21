@props(['title', 'subtitle' => null, 'items' => []])

<div class="hero position-relative py-5">
    <div class="hero__blob hero__blob--accent" style="width: 280px; height: 280px; top: -100px; right: 5%;"></div>
    <div class="hero__blob hero__blob--blue" style="width: 240px; height: 240px; bottom: -120px; left: 5%;"></div>

    <div class="container position-relative py-4 text-white">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb modern-breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
                @foreach ($items as $label => $url)
                    @if ($loop->last || ! $url)
                        <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
        <h1 class="reveal fw-bold display-5 mb-2">{{ $title }}</h1>
        @if ($subtitle)
            <p class="reveal lead text-white-50 mb-0" style="max-width: 640px; transition-delay: .1s;">{{ $subtitle }}</p>
        @endif
    </div>
</div>
