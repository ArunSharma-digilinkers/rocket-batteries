@extends('admin.layouts.app')

@section('title', 'Warranties')

@section('content')
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach (['pending' => 'Pending', 'verified' => 'Verified', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Serial No.</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($warranties as $warranty)
                <tr>
                    <td>{{ $warranty->created_at->format('d M Y') }}</td>
                    <td>{{ $warranty->customer_name }}</td>
                    <td>{{ $warranty->product->name ?? '—' }}</td>
                    <td><code>{{ $warranty->serial_no }}</code></td>
                    <td>
                        <span class="badge bg-{{ ['pending' => 'warning', 'verified' => 'success', 'rejected' => 'danger'][$warranty->status] ?? 'secondary' }}">
                            {{ ucfirst($warranty->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.warranties.show', $warranty) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No warranty registrations yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $warranties->links() }}
@endsection
