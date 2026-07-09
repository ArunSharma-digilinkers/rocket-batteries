@extends('admin.layouts.app')

@section('title', 'Series')

@section('actions')
    <a href="{{ route('admin.series.create') }}" class="btn btn-primary btn-sm">Add Series</a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Products</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($seriesList as $series)
                <tr>
                    <td>{{ $series->name }}</td>
                    <td>{{ $series->category->name }}</td>
                    <td>{{ $series->products_count }}</td>
                    <td>
                        <span class="badge bg-{{ $series->status ? 'success' : 'secondary' }}">
                            {{ $series->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.series.edit', $series) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.series.destroy', $series) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this series?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No series yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
