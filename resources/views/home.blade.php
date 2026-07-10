@extends('layouts.app')

@section('title', config('app.name') . ' — Industrial Battery Solutions')

@section('content')
    {{-- Slider --}}
    @if ($sliders->isNotEmpty())
        <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($sliders as $slide)
                    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($sliders as $slide)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="bg-dark text-white text-center py-5" style="min-height: 360px;">
                            <div class="container py-5">
                                @if ($slide->title)
                                    <h1 class="display-5 fw-bold">{{ $slide->title }}</h1>
                                @endif
                                @if ($slide->subtitle)
                                    <p class="lead">{{ $slide->subtitle }}</p>
                                @endif
                                @if ($slide->cta_text && $slide->cta_url)
                                    <a href="{{ $slide->cta_url }}" class="btn btn-primary btn-lg mt-3">{{ $slide->cta_text }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($sliders->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            @endif
        </div>
    @endif

    {{-- About teaser --}}
    <section class="container py-5 text-center">
        <h2 class="fw-bold">Reliable Power for Every Application</h2>
        <p class="lead text-muted mx-auto" style="max-width: 720px;">
            Rocket Batteries India manufactures industrial batteries for UPS, solar, telecom, EV and standby
            power applications — backed by advanced filtering, downloadable datasheets, and a warranty
            registration portal.
        </p>
    </section>

    {{-- Featured products --}}
    @if ($featuredProducts->isNotEmpty())
        <section class="bg-light py-5">
            <div class="container">
                <h2 class="fw-bold mb-4">Featured Products</h2>
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-sm-6 col-lg-4">
                            <div class="card h-100">
                                @if ($product->hero_image)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($product->hero_image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @endif
                                <div class="card-body">
                                    <div class="text-muted small">{{ $product->series->category->name }} — {{ $product->series->name }}</div>
                                    <h3 class="h5 card-title">{{ $product->name }}</h3>
                                    <p class="card-text text-muted small">{{ $product->short_description }}</p>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">View Details</a>
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
        <section class="container py-5">
            <h2 class="fw-bold mb-4 text-center">Applications</h2>
            <div class="row g-3 text-center">
                @foreach ($applications as $application)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold">{{ $application->name }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Clients --}}
    @if ($clients->isNotEmpty())
        <section class="bg-light py-5">
            <div class="container">
                <h2 class="fw-bold mb-4 text-center">Our Clients</h2>
                <div class="row g-4 align-items-center justify-content-center">
                    @foreach ($clients as $client)
                        <div class="col-4 col-md-2 text-center">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($client->logo) }}" alt="{{ $client->name }}" class="img-fluid" style="max-height: 60px;">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if ($testimonials->isNotEmpty())
        <section class="container py-5">
            <h2 class="fw-bold mb-4 text-center">What Our Customers Say</h2>
            <div class="row g-4">
                @foreach ($testimonials as $testimonial)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <p class="card-text">&ldquo;{{ $testimonial->content }}&rdquo;</p>
                                <div class="fw-semibold">{{ $testimonial->name }}</div>
                                <div class="text-muted small">{{ $testimonial->designation }}{{ $testimonial->company ? ', ' . $testimonial->company : '' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="bg-dark text-white text-center py-5">
        <div class="container">
            <h2 class="fw-bold">Need Help Choosing the Right Battery?</h2>
            <p class="lead">Browse our full catalog and request a quote for any product.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Browse Catalog</a>
        </div>
    </section>
@endsection
