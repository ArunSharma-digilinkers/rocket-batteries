@extends('admin.layouts.app')

@section('title', 'Edit Attribute')

@section('content')
    <form method="POST" action="{{ route('admin.attributes.update', $attribute) }}">
        @csrf
        @method('PUT')
        @include('admin.attributes._form')
    </form>
@endsection
