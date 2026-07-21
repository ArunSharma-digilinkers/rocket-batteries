@extends('layouts.app')

@section('title', 'Gallery — ' . config('app.name'))
@section('meta_description', 'Browse photos from Rocket Batteries manufacturing, products, events, exhibitions, and company activities.')

@section('content')
    <x-page-header
        title="Gallery"
        subtitle="A glimpse into our products, facilities, events, and the people behind Rocket Batteries."
        :items="['Gallery' => null]"
    />

    <section
        class="gallery-simple"
        x-data="{ modal: null }"
        @keydown.escape.window="modal = null"
    >
        <div class="container">
            <div class="gallery-simple__header">
                <div>
                    <h2>Explore Rocket Batteries</h2>
                    <p>Browse our latest photographs and company moments.</p>
                </div>
            </div>

            @if ($images->isNotEmpty())
                <div class="row g-4 gallery-simple__grid">
                    @foreach ($images as $image)
                        <div class="col-sm-6 col-lg-4">
                            <button
                                type="button"
                                class="gallery-simple__card"
                                @click="modal = @js(['url' => $image->display_url, 'caption' => $image->caption ?: 'Gallery image'])"
                            >
                                <img src="{{ $image->display_url }}" alt="{{ $image->caption ?: 'Rocket Batteries gallery image' }}" loading="lazy">
                                <span class="gallery-simple__overlay">
                                    <span><strong>{{ $image->caption ?: 'Rocket Batteries' }}</strong></span>
                                    <i class="bi bi-zoom-in"></i>
                                </span>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="gallery-modal" x-show="modal" x-cloak x-transition.opacity role="dialog" aria-modal="true" aria-label="Gallery image" @click.self="modal = null">
                    <div class="gallery-modal__box">
                        <button type="button" class="gallery-modal__close" @click="modal = null" aria-label="Close"><i class="bi bi-x-lg"></i></button>
                        <img :src="modal?.url" :alt="modal?.caption || ''">
                        <div class="gallery-modal__caption">
                            <strong x-text="modal?.caption"></strong>
                        </div>
                    </div>
                </div>
            @else
                <div class="gallery-simple__empty">
                    <i class="bi bi-images"></i>
                    <h3>No gallery images yet</h3>
                    <p>New photographs will appear here after they are uploaded from the admin panel.</p>
                </div>
            @endif
        </div>
    </section>
@endsection
