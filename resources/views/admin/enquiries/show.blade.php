@extends('admin.layouts.app')

@section('title', 'Enquiry #' . $enquiry->id)

@section('actions')
    <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-link">Back to Inbox</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Type</dt>
                        <dd class="col-sm-9">{{ ucfirst($enquiry->type) }}</dd>

                        @if ($enquiry->product)
                            <dt class="col-sm-3">Product</dt>
                            <dd class="col-sm-9"><a href="{{ route('admin.products.edit', $enquiry->product) }}">{{ $enquiry->product->name }}</a></dd>
                        @endif

                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9">{{ $enquiry->name }}</dd>

                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd>

                        @if ($enquiry->phone)
                            <dt class="col-sm-3">Phone</dt>
                            <dd class="col-sm-9">{{ $enquiry->phone }}</dd>
                        @endif

                        @if ($enquiry->company)
                            <dt class="col-sm-3">Company</dt>
                            <dd class="col-sm-9">{{ $enquiry->company }}</dd>
                        @endif

                        <dt class="col-sm-3">Received</dt>
                        <dd class="col-sm-9">{{ $enquiry->created_at->format('d M Y, H:i') }}</dd>

                        @if ($enquiry->message)
                            <dt class="col-sm-3">Message</dt>
                            <dd class="col-sm-9">{{ $enquiry->message }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Status</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select mb-2">
                            @foreach (['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $value => $label)
                                <option value="{{ $value }}" {{ $enquiry->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Delete</button>
            </form>
        </div>
    </div>
@endsection
