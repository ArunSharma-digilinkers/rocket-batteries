@extends('admin.layouts.app')
@section('title', 'Edit Blog Post')
@section('actions') @if ($post->status)<a href="{{ route('blog.show', $post) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> View Article</a>@endif @endsection
@section('content')
    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.blog._form')</form>
@endsection
