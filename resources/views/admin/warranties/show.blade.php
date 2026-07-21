@extends('admin.layouts.app')

@section('title', 'Warranty #' . $warranty->id)

@section('actions')
    <a href="{{ route('admin.warranties.index') }}" class="btn btn-sm btn-link">Back to List</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Product</dt>
                        <dd class="col-sm-9">
                            @if ($warranty->product)
                                <a href="{{ route('admin.products.edit', $warranty->product) }}">{{ $warranty->product->name }}</a>
                            @else
                                —
                            @endif
                        </dd>

                        <dt class="col-sm-3">Serial No.</dt>
                        <dd class="col-sm-9"><code>{{ $warranty->serial_no }}</code></dd>

                        <dt class="col-sm-3">Customer</dt>
                        <dd class="col-sm-9">{{ $warranty->customer_name }}</dd>

                        <dt class="col-sm-3">Mobile</dt>
                        <dd class="col-sm-9">{{ $warranty->mobile }}</dd>

                        @if ($warranty->email)
                            <dt class="col-sm-3">Email</dt>
                            <dd class="col-sm-9"><a href="mailto:{{ $warranty->email }}">{{ $warranty->email }}</a></dd>
                        @endif

                        <dt class="col-sm-3">Purchase Date</dt>
                        <dd class="col-sm-9">{{ $warranty->purchase_date->format('d M Y') }}</dd>

                        @if ($warranty->dealer_name)
                            <dt class="col-sm-3">Dealer</dt>
                            <dd class="col-sm-9">{{ $warranty->dealer_name }}</dd>
                        @endif

                        @if ($warranty->invoice_path)
                            <dt class="col-sm-3">Invoice</dt>
                            <dd class="col-sm-9">
                                <a href="{{ \Illuminate\Support\Facades\Storage::url($warranty->invoice_path) }}" target="_blank">View Uploaded Invoice</a>
                            </dd>
                        @endif

                        <dt class="col-sm-3">Registered</dt>
                        <dd class="col-sm-9">{{ $warranty->created_at->format('d M Y, H:i') }}</dd>

                        @if ($warranty->verified_at)
                            <dt class="col-sm-3">Verified</dt>
                            <dd class="col-sm-9">
                                {{ $warranty->verified_at->format('d M Y, H:i') }}
                                @if ($warranty->verifier)
                                    by {{ $warranty->verifier->name }}
                                @endif
                            </dd>
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
                    <form method="POST" action="{{ route('admin.warranties.update', $warranty) }}">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select mb-2">
                            @foreach (['pending' => 'Pending', 'verified' => 'Verified', 'rejected' => 'Rejected'] as $value => $label)
                                <option value="{{ $value }}" {{ $warranty->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <textarea name="notes" class="form-control mb-2" rows="3" placeholder="Internal notes (optional)">{{ old('notes', $warranty->notes) }}</textarea>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.warranties.destroy', $warranty) }}" onsubmit="return confirm('Delete this warranty registration?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">Delete</button>
            </form>
        </div>
    </div>
@endsection
