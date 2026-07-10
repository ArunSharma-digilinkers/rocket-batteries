@extends('layouts.app')

@section('title', $category->name . ' — ' . config('app.name'))
@section('meta_description', $category->description)

@section('content')
    <x-breadcrumbs :items="['Catalog' => route('products.index'), $category->name => null]" />

    <div class="container pb-5">
        <h1 class="fw-bold mb-2">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="text-muted mb-4">{{ $category->description }}</p>
        @endif

        @if ($category->series->isNotEmpty())
            <div class="row g-3 mb-4">
                @foreach ($category->series as $series)
                    <div class="col-sm-6 col-lg-3">
                        <a href="{{ route('series.show', $series) }}" class="card h-100 text-decoration-none">
                            <div class="card-body">
                                <h2 class="h6 card-title mb-1">{{ $series->name }}</h2>
                                <p class="card-text text-muted small mb-0">{{ \Illuminate\Support\Str::limit($series->description, 80) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <livewire:catalog.product-list :initial-category="$category->id" :key="'cat-'.$category->id" />
    </div>
@endsection
