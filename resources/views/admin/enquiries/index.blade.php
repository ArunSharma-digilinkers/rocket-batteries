@extends('admin.layouts.app')

@section('title', 'Enquiries')

@section('content')
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach (['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $value => $label)
                    <option value="{{ $value }}" {{ ($filters['status'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach (['general' => 'General', 'quote' => 'Quote Request'] as $value => $label)
                    <option value="{{ $value }}" {{ ($filters['type'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Name</th>
                <th>Product</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($enquiries as $enquiry)
                <tr>
                    <td>{{ $enquiry->created_at->format('d M Y, H:i') }}</td>
                    <td><span class="badge bg-{{ $enquiry->type === 'quote' ? 'info' : 'secondary' }}">{{ ucfirst($enquiry->type) }}</span></td>
                    <td>{{ $enquiry->name }}</td>
                    <td>{{ $enquiry->product->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ ['new' => 'warning', 'contacted' => 'info', 'closed' => 'success'][$enquiry->status] ?? 'secondary' }}">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No enquiries yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $enquiries->links() }}
@endsection
