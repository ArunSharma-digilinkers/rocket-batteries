@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Product</a>
@endsection

@section('content')
    <div class="admin-welcome mb-4">
        <div>
            <span>Welcome back, {{ auth('admin')->user()->name }}</span>
            <h2>Here’s what’s happening today.</h2>
            <p>Manage your product catalog, customer requests, warranties, and website content from one place.</p>
        </div>
        <span class="admin-welcome__icon"><i class="bi bi-lightning-charge-fill"></i></span>
    </div>

    <div class="admin-stat-grid">
        @foreach ([
            ['label' => 'Categories', 'value' => $counts['categories'], 'icon' => 'bi-folder-fill', 'url' => route('admin.categories.index'), 'tone' => 'blue'],
            ['label' => 'Battery Series', 'value' => $counts['series'], 'icon' => 'bi-collection-fill', 'url' => route('admin.series.index'), 'tone' => 'indigo'],
            ['label' => 'Products', 'value' => $counts['products'], 'icon' => 'bi-battery-full', 'url' => route('admin.products.index'), 'tone' => 'orange'],
            ['label' => 'Applications', 'value' => $counts['applications'], 'icon' => 'bi-diagram-3-fill', 'url' => route('admin.applications.index'), 'tone' => 'teal'],
            ['label' => 'New Enquiries', 'value' => $counts['enquiries'], 'icon' => 'bi-chat-left-text-fill', 'url' => route('admin.enquiries.index'), 'tone' => 'amber'],
            ['label' => 'Pending Warranties', 'value' => $counts['warranties'], 'icon' => 'bi-shield-check', 'url' => route('admin.warranties.index'), 'tone' => 'green'],
        ] as $stat)
            <a href="{{ $stat['url'] }}" class="admin-stat-card admin-stat-card--{{ $stat['tone'] }}">
                <span class="admin-stat-card__icon"><i class="bi {{ $stat['icon'] }}"></i></span>
                <span><small>{{ $stat['label'] }}</small><strong>{{ $stat['value'] }}</strong></span>
                <i class="bi bi-arrow-up-right"></i>
            </a>
        @endforeach
    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-8">
            <div class="card admin-quick-card h-100">
                <div class="card-header bg-white"><div><small>Shortcuts</small><strong>Quick actions</strong></div></div>
                <div class="card-body">
                    <div class="admin-quick-grid">
                        <a href="{{ route('admin.products.create') }}"><span><i class="bi bi-plus-circle-fill"></i></span><strong>Add product</strong><small>Create a catalog listing</small></a>
                        <a href="{{ route('admin.gallery.index') }}"><span><i class="bi bi-cloud-arrow-up-fill"></i></span><strong>Upload images</strong><small>Update the public gallery</small></a>
                        <a href="{{ route('admin.enquiries.index') }}"><span><i class="bi bi-inbox-fill"></i></span><strong>View enquiries</strong><small>Respond to new leads</small></a>
                        <a href="{{ route('admin.warranties.index') }}"><span><i class="bi bi-patch-check-fill"></i></span><strong>Verify warranty</strong><small>Review registrations</small></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card admin-site-card h-100">
                <div class="card-body">
                    <span class="admin-site-card__icon"><i class="bi bi-globe2"></i></span>
                    <small>Public website</small>
                    <h3>Review your latest changes.</h3>
                    <p>Open the live site in a new tab to check products, gallery images, and page content.</p>
                    <a href="{{ route('home') }}" target="_blank">View website <i class="bi bi-arrow-up-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection
