@extends('admin.layouts.app')

@section('title', 'Attributes')

@section('actions')
    <a href="{{ route('admin.attributes.create') }}" class="btn btn-primary btn-sm">Add Attribute</a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Group</th>
                <th>Unit</th>
                <th>Type</th>
                <th>Filterable</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attributes as $attribute)
                <tr>
                    <td>{{ $attribute->name }}</td>
                    <td>{{ $attribute->group }}</td>
                    <td>{{ $attribute->unit }}</td>
                    <td>{{ $attribute->data_type }}</td>
                    <td>
                        <span class="badge bg-{{ $attribute->is_filterable ? 'success' : 'secondary' }}">
                            {{ $attribute->is_filterable ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.attributes.edit', $attribute) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.attributes.destroy', $attribute) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this attribute?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No attributes yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
