@extends('layouts.app')

@section('title', $series->name . ' — ' . config('app.name'))
@section('meta_description', $series->description)

@section('content')
    <x-breadcrumbs :items="[
        'Catalog' => route('products.index'),
        $series->category->name => route('categories.show', $series->category),
        $series->name => null,
    ]" />

    <div class="container pb-5">
        <h1 class="fw-bold mb-2">{{ $series->category->name }} — {{ $series->name }}</h1>
        @if ($series->description)
            <p class="text-muted mb-4">{{ $series->description }}</p>
        @endif

        <livewire:catalog.product-list :initial-series="[$series->id]" :key="'series-'.$series->id" />
    </div>
@endsection
