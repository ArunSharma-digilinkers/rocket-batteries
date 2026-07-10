@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name) . ' — ' . config('app.name'))
@section('meta_description', $product->meta_description ?: $product->short_description)

@section('content')
    <x-breadcrumbs :items="[
        'Catalog' => route('products.index'),
        $product->series->category->name => route('categories.show', $product->series->category),
        $product->series->name => route('series.show', $product->series),
        $product->name => null,
    ]" />

    @php
        $specValues = $product->attributeValues->keyBy('attribute_id');
        $galleryImages = $product->media->where('type', 'image');
    @endphp

    <div class="container pb-5">
        <div class="row g-5">
            <div class="col-lg-6">
                @if ($product->hero_image)
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($product->hero_image) }}" class="img-fluid rounded mb-3" alt="{{ $product->name }}">
                @endif

                @if ($galleryImages->isNotEmpty())
                    <div class="row g-2">
                        @foreach ($galleryImages as $media)
                            <div class="col-3">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($media->path) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="text-muted small">{{ $product->series->category->name }} — {{ $product->series->name }}</div>
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <p class="text-muted">SKU: <code>{{ $product->sku }}</code>@if ($product->nominal_voltage) &middot; {{ $product->nominal_voltage }} @endif</p>

                @if ($product->short_description)
                    <p class="lead">{{ $product->short_description }}</p>
                @endif

                @if ($product->applications->isNotEmpty())
                    <div class="mb-3">
                        @foreach ($product->applications as $application)
                            <span class="badge bg-secondary">{{ $application->name }}</span>
                        @endforeach
                    </div>
                @endif

                @if ($product->datasheet_path)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($product->datasheet_path) }}" target="_blank" class="btn btn-outline-primary mb-4">
                        Download Datasheet (PDF)
                    </a>
                @endif

                <div class="card">
                    <div class="card-header">Request a Quote</div>
                    <div class="card-body">
                        <livewire:catalog.request-quote-form :product-id="$product->id" :key="'quote-'.$product->id" />
                    </div>
                </div>
            </div>
        </div>

        @if ($product->series->attributes->isNotEmpty())
            <div class="row mt-5">
                <div class="col-lg-8">
                    <h2 class="h4 fw-bold mb-3">Specifications</h2>
                    <table class="table table-striped">
                        <tbody>
                            @foreach ($product->series->attributes as $attribute)
                                @php $value = $specValues->get($attribute->id); @endphp
                                @if ($value)
                                    <tr>
                                        <th class="w-50">{{ $attribute->name }} @if ($attribute->unit)<span class="text-muted small">({{ $attribute->unit }})</span>@endif</th>
                                        <td>{{ $value->value }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($product->long_description)
            <div class="row mt-4">
                <div class="col-lg-8">
                    <h2 class="h4 fw-bold mb-3">Description</h2>
                    <div>{{ $product->long_description }}</div>
                </div>
            </div>
        @endif
    </div>
@endsection
