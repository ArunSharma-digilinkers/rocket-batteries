@extends('admin.layouts.app')

@section('title', 'Edit Application')

@section('content')
    <form method="POST" action="{{ route('admin.applications.update', $application) }}">
        @csrf
        @method('PUT')
        @include('admin.applications._form')
    </form>
@endsection
