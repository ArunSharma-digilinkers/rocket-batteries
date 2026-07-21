@extends('admin.layouts.app')
@section('title', 'Create Blog Post')
@section('content')
    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">@csrf @include('admin.blog._form')</form>
@endsection
