@extends('admin.layouts.app')

@section('title', 'Add Attribute')

@section('content')
    <form method="POST" action="{{ route('admin.attributes.store') }}">
        @csrf
        @include('admin.attributes._form')
    </form>
@endsection
