@extends('admin.layouts.app')

@section('title', 'Add Application')

@section('content')
    <form method="POST" action="{{ route('admin.applications.store') }}">
        @csrf
        @include('admin.applications._form')
    </form>
@endsection
