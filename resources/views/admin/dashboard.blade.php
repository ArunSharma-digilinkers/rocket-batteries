@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3">
        @foreach ([
            'Categories' => $counts['categories'],
            'Series' => $counts['series'],
            'Products' => $counts['products'],
            'Applications' => $counts['applications'],
            'New Enquiries' => $counts['enquiries'],
            'Pending Warranties' => $counts['warranties'],
        ] as $label => $count)
            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small">{{ $label }}</div>
                        <div class="fs-2 fw-bold">{{ $count }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
