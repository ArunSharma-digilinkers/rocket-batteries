@extends('admin.layouts.app')

@section('title', 'Edit Series')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.series.update', $series) }}">
                @csrf
                @method('PUT')
                @include('admin.series._form')
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Spec Template</div>
        <div class="card-body">
            <p class="text-muted small">Choose which attributes belong to this series' spec table, whether each is required, and their display order.</p>

            <form method="POST" action="{{ route('admin.series.attributes.sync', $series) }}">
                @csrf

                @php $attached = $series->attributes->keyBy('id'); @endphp

                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th style="width: 40px;"></th>
                            <th>Attribute</th>
                            <th>Unit</th>
                            <th style="width: 100px;">Required</th>
                            <th style="width: 100px;">Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allAttributes as $attribute)
                            @php $pivot = $attached->get($attribute->id)?->pivot; @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input" name="attributes[]" value="{{ $attribute->id }}" {{ $pivot ? 'checked' : '' }}>
                                </td>
                                <td>{{ $attribute->name }}</td>
                                <td>{{ $attribute->unit }}</td>
                                <td>
                                    <input type="checkbox" class="form-check-input" name="required[]" value="{{ $attribute->id }}" {{ $pivot?->is_required ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" name="sort_order[{{ $attribute->id }}]" value="{{ $pivot->sort_order ?? 0 }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary btn-sm">Save Spec Template</button>
            </form>
        </div>
    </div>
@endsection
