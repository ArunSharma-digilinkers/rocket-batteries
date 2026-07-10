@extends('layouts.app')

@section('title', 'Product Catalog — ' . config('app.name'))

@section('content')
    <x-breadcrumbs :items="['Catalog' => null]" />

    <div class="container pb-5">
        <h1 class="fw-bold mb-4">Product Catalog</h1>

        <livewire:catalog.product-list />
    </div>
@endsection
