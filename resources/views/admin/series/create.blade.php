@extends('admin.layouts.app')

@section('title', 'Add Series')

@section('content')
    <form method="POST" action="{{ route('admin.series.store') }}">
        @csrf
        @include('admin.series._form')
    </form>
@endsection
