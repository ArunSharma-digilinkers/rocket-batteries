@extends('admin.layouts.app')

@section('title', 'Products')

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Add Product</a>
@endsection

@section('content')
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Series</th>
                <th>Featured</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td><code>{{ $product->sku }}</code></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->series->category->name }} — {{ $product->series->name }}</td>
                    <td>{{ $product->is_featured ? 'Yes' : '' }}</td>
                    <td>
                        <span class="badge bg-{{ $product->status ? 'success' : 'secondary' }}">
                            {{ $product->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No products yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
