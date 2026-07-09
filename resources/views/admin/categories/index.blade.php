@extends('admin.layouts.app')

@section('title', 'Categories')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Add Category</a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Series</th>
                <th>Status</th>
                <th>Sort</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td><code>{{ $category->slug }}</code></td>
                    <td>{{ $category->series_count }}</td>
                    <td>
                        <span class="badge bg-{{ $category->status ? 'success' : 'secondary' }}">
                            {{ $category->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $category->sort_order }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No categories yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
