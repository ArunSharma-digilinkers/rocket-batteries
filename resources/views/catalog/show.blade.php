@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name) . ' — ' . config('app.name'))
@section('meta_description', $product->meta_description ?: $product->short_description)
@if ($product->hero_image)
    @section('og_image', url(\Illuminate\Support\Facades\Storage::url($product->hero_image)))
@endif

@section('structured_data')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->name,
        'sku' => $product->sku,
        'description' => $product->short_description ?: $product->meta_description,
        'image' => $product->hero_image ? url(\Illuminate\Support\Facades\Storage::url($product->hero_image)) : null,
        'brand' => ['@type' => 'Brand', 'name' => config('app.name')],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
    @php
        $specValues = $product->attributeValues->keyBy('attribute_id');
        $productImages = collect();

        if ($product->hero_image) {
            $productImages->push(['url' => \Illuminate\Support\Facades\Storage::url($product->hero_image), 'alt' => $product->name]);
        }

        foreach ($product->media->where('type', 'image') as $media) {
            $productImages->push(['url' => $media->url(), 'alt' => $product->name . ' product image']);
        }

        $productImages = $productImages->unique('url')->values();
        $availableSpecs = $product->series->attributes
            ->map(fn ($attribute) => ['attribute' => $attribute, 'value' => $specValues->get($attribute->id)])
            ->filter(fn ($spec) => filled($spec['value']?->value));
        $specGroups = $availableSpecs->groupBy(fn ($spec) => $spec['attribute']->group ?: 'Technical specifications');
    @endphp

    <section class="product-detail-hero">
        <div class="product-detail-hero__ambient" aria-hidden="true"></div>
        <div class="container">
            <nav class="product-detail-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}"><i class="bi bi-house-door-fill"></i> Home</a><i class="bi bi-chevron-right"></i>
                <a href="{{ route('products.index') }}">Products</a><i class="bi bi-chevron-right"></i>
                <a href="{{ route('series.show', $product->series) }}">{{ $product->series->name }} Series</a><i class="bi bi-chevron-right"></i>
                <span>{{ $product->name }}</span>
            </nav>

            <div class="row g-4 g-xl-5 align-items-center">
                <div class="col-lg-6">
                    <div class="product-visual" x-data="{ active: @js($productImages->first()), zoomOpen: false }" @keydown.escape.window="zoomOpen = false">
                        <div class="product-visual__stage">
                            <span class="product-visual__series">{{ $product->series->name }} Series</span>
                            @if ($productImages->isNotEmpty())
                                <button type="button" class="product-visual__zoom" @click="zoomOpen = true" aria-label="Enlarge product image"><i class="bi bi-arrows-fullscreen"></i></button>
                                <img :src="active.url" :alt="active.alt" class="product-visual__image">
                            @else
                                <div class="product-visual__placeholder"><i class="bi bi-battery-full"></i><span>Product image coming soon</span></div>
                            @endif
                            <span class="product-visual__quality"><i class="bi bi-patch-check-fill"></i> Quality assured</span>
                        </div>

                        @if ($productImages->count() > 1)
                            <div class="product-visual__thumbs" aria-label="Product images">
                                @foreach ($productImages as $image)
                                    <button type="button" @click="active = @js($image)" :class="active.url === @js($image['url']) ? 'active' : ''" aria-label="View product image {{ $loop->iteration }}">
                                        <img src="{{ $image['url'] }}" alt="" loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        @if ($productImages->isNotEmpty())
                            <div class="product-image-modal" x-show="zoomOpen" x-cloak x-transition.opacity @click.self="zoomOpen = false" role="dialog" aria-modal="true" aria-label="Product image preview">
                                <button type="button" @click="zoomOpen = false" aria-label="Close image preview"><i class="bi bi-x-lg"></i></button>
                                <img :src="active.url" :alt="active.alt">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product-summary">
                        <span class="product-summary__eyebrow"><i></i>{{ $product->series->category->name }} · {{ $product->series->name }} Series</span>
                        <h1>{{ $product->name }}</h1>
                        <div class="product-summary__meta">
                            <span><small>Product code</small><strong>{{ $product->sku }}</strong></span>
                            @if ($product->nominal_voltage)<span><small>Nominal voltage</small><strong>{{ $product->nominal_voltage }}</strong></span>@endif
                            <span class="product-summary__available"><i></i> Available for enquiry</span>
                        </div>

                        @if ($product->short_description)<p class="product-summary__lead">{{ $product->short_description }}</p>@endif

                        @if ($product->applications->isNotEmpty())
                            <div class="product-summary__applications">
                                <small>Recommended applications</small>
                                <div>@foreach ($product->applications as $application)<span><i class="bi bi-check2"></i>{{ $application->name }}</span>@endforeach</div>
                            </div>
                        @endif

                        @if ($availableSpecs->isNotEmpty())
                            <div class="product-summary__quick-specs">
                                @foreach ($availableSpecs->take(3) as $spec)
                                    <div><span><i class="bi bi-speedometer2"></i></span><small>{{ $spec['attribute']->name }}</small><strong>{{ $spec['value']->value }}{{ $spec['attribute']->unit ? ' ' . $spec['attribute']->unit : '' }}</strong></div>
                                @endforeach
                            </div>
                        @endif

                        <div class="product-summary__actions">
                            <a href="#request-quote" class="btn btn-accent">Request a Quote <i class="bi bi-arrow-right"></i></a>
                            @if ($product->datasheet_path)
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($product->datasheet_path) }}" target="_blank" class="product-summary__datasheet"><i class="bi bi-file-earmark-pdf-fill"></i><span><strong>Download datasheet</strong><small>Technical PDF</small></span></a>
                            @endif
                        </div>

                        <div class="product-summary__trust"><span><i class="bi bi-shield-check"></i> Reliable performance</span><span><i class="bi bi-headset"></i> Expert support</span><span><i class="bi bi-truck"></i> Pan-India supply</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product-information">
        <div class="container">
            <div class="row g-4 g-xl-5 align-items-start">
                <div class="col-lg-7 col-xl-8">
                    @if ($product->long_description)
                        <div class="product-information__intro"><span class="section-kicker">Product overview</span><h2>Engineered for dependable power.</h2></div>
                        <div class="product-description">{!! nl2br(e($product->long_description)) !!}</div>
                    @endif

                    @if ($availableSpecs->isNotEmpty())
                        <div class="product-specifications">
                            <div class="product-specifications__head"><div><span class="section-kicker">Technical data</span><h2>Product specifications</h2></div><span><i class="bi bi-info-circle"></i> Values may vary by configuration</span></div>
                            @foreach ($specGroups as $group => $specs)
                                <div class="product-spec-group">
                                    <h3><span><i class="bi bi-sliders"></i></span>{{ $group }}</h3>
                                    <dl>
                                        @foreach ($specs as $spec)
                                            <div><dt>{{ $spec['attribute']->name }}</dt><dd>{{ $spec['value']->value }}@if ($spec['attribute']->unit)<small>{{ $spec['attribute']->unit }}</small>@endif</dd></div>
                                        @endforeach
                                    </dl>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-lg-5 col-xl-4" id="request-quote">
                    <aside class="product-quote-card">
                        <div class="product-quote-card__head"><span><i class="bi bi-chat-square-text-fill"></i></span><div><small>Talk to our battery experts</small><h2>Request a quote</h2></div></div>
                        <p>Share your requirement and our team will respond with the right product and commercial details.</p>
                        <livewire:catalog.request-quote-form :product-id="$product->id" :key="'quote-'.$product->id" />
                        <div class="product-quote-card__foot"><span><i class="bi bi-lock-fill"></i> Your details stay private</span><span><i class="bi bi-clock-fill"></i> Quick response</span></div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="product-related">
            <div class="container">
                <div class="product-related__head"><div><span class="section-kicker">More in this range</span><h2>{{ $product->series->name }} Series products</h2></div><a href="{{ route('series.show', $product->series) }}">View entire series <i class="bi bi-arrow-right"></i></a></div>
                <div class="row g-4">
                    @foreach ($relatedProducts as $related)
                        <div class="col-md-4">
                            <a href="{{ route('products.show', $related) }}" class="related-product-card">
                                <div>@if ($related->hero_image)<img src="{{ \Illuminate\Support\Facades\Storage::url($related->hero_image) }}" alt="{{ $related->name }}" loading="lazy">@else<i class="bi bi-battery-full"></i>@endif</div>
                                <span><small>{{ $related->sku }}</small><strong>{{ $related->name }}</strong>@if ($related->nominal_voltage)<em>{{ $related->nominal_voltage }}</em>@endif</span><i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
