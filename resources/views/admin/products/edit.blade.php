@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
    <livewire:admin.product-form :product-id="$product->id" :key="'product-'.$product->id" />
@endsection
