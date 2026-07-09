@extends('admin.layouts.app')

@section('title', 'Applications')

@section('actions')
    <a href="{{ route('admin.applications.create') }}" class="btn btn-primary btn-sm">Add Application</a>
@endsection

@section('content')
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Products</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $application)
                <tr>
                    <td>{{ $application->name }}</td>
                    <td>{{ $application->products_count }}</td>
                    <td>
                        <span class="badge bg-{{ $application->status ? 'success' : 'secondary' }}">
                            {{ $application->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.applications.edit', $application) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this application?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No applications yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
