@extends('layouts.app')

@section('title', 'Contact Us — ' . config('app.name'))

@section('content')
    <x-breadcrumbs :items="['Contact' => null]" />

    <div class="container pb-5">
        <h1 class="fw-bold mb-4">Contact Us</h1>

        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Send Us a Message</div>
                    <div class="card-body">
                        <livewire:catalog.general-enquiry-form />
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="h5 fw-bold mb-3">Get in Touch</h2>
                <ul class="list-unstyled mb-4">
                    @if ($settings['address'])
                        <li class="mb-2">{{ $settings['address'] }}</li>
                    @endif
                    @if ($settings['phone'])
                        <li class="mb-2"><a href="tel:{{ $settings['phone'] }}">{{ $settings['phone'] }}</a></li>
                    @endif
                    @if ($settings['email'])
                        <li class="mb-2"><a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a></li>
                    @endif
                    @if ($settings['whatsapp'])
                        <li class="mb-2">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp']) }}" target="_blank" rel="noopener">
                                Chat on WhatsApp
                            </a>
                        </li>
                    @endif
                </ul>

                @if ($settings['facebook'] || $settings['linkedin'] || $settings['instagram'])
                    <div class="mb-4">
                        @if ($settings['facebook'])
                            <a href="{{ $settings['facebook'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary me-2">Facebook</a>
                        @endif
                        @if ($settings['linkedin'])
                            <a href="{{ $settings['linkedin'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary me-2">LinkedIn</a>
                        @endif
                        @if ($settings['instagram'])
                            <a href="{{ $settings['instagram'] }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Instagram</a>
                        @endif
                    </div>
                @endif

                @if ($settings['mapEmbedUrl'])
                    <div class="ratio ratio-4x3">
                        <iframe src="{{ $settings['mapEmbedUrl'] }}" style="border:0;" allowfullscreen loading="lazy"></iframe>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
