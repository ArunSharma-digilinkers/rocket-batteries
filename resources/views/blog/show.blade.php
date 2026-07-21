@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' — ' . config('app.name'))
@section('meta_description', $post->meta_description ?: $post->excerpt ?: Str::limit(strip_tags($post->content), 155))
@if ($post->featured_image_url) @section('og_image', url($post->featured_image_url)) @endif

@section('structured_data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->excerpt,
    'image' => $post->featured_image_url ? url($post->featured_image_url) : null,
    'datePublished' => ($post->published_at ?? $post->created_at)->toAtomString(),
    'dateModified' => $post->updated_at->toAtomString(),
    'author' => ['@type' => 'Organization', 'name' => $post->author ?: config('app.name')],
    'publisher' => ['@type' => 'Organization', 'name' => config('app.name')],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection

@section('content')
    <article class="blog-detail">
        <header class="blog-detail__hero">
            <div class="container">
                <nav class="blog-detail__crumbs" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}">Home</a><i class="bi bi-chevron-right"></i>
                    <a href="{{ route('blog.index') }}">Blog</a><i class="bi bi-chevron-right"></i>
                    <span>Article</span>
                </nav>
                @if ($post->category)<a class="blog-detail__category" href="{{ route('blog.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a>@endif
                <h1>{{ $post->title }}</h1>
                @if ($post->excerpt)<p class="blog-detail__lead">{{ $post->excerpt }}</p>@endif
                <div class="blog-detail__meta">
                    <span><i class="bi bi-calendar3"></i> {{ ($post->published_at ?? $post->created_at)->format('d F Y') }}</span>
                    <span><i class="bi bi-person-circle"></i> {{ $post->author ?: 'Rocket Batteries' }}</span>
                    <span><i class="bi bi-clock"></i> {{ max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 220)) }} min read</span>
                </div>
            </div>
        </header>

        <div class="container blog-detail__container">
            @if ($post->featured_image_url)
                <figure class="blog-detail__image"><img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"></figure>
            @endif
            <div class="blog-detail__layout">
                <div class="blog-detail__content ck-content">{!! $post->content !!}</div>
                <aside class="blog-detail__aside">
                    <div class="blog-share">
                        <span>Share this article</span>
                        <div>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="bi bi-whatsapp"></i></a>
                            <a href="mailto:?subject={{ rawurlencode($post->title) }}&body={{ rawurlencode(url()->current()) }}" aria-label="Share by email"><i class="bi bi-envelope"></i></a>
                        </div>
                    </div>
                    <div class="blog-cta">
                        <i class="bi bi-battery-charging"></i>
                        <h3>Need the right battery?</h3>
                        <p>Our team can help you find a dependable power solution.</p>
                        <a href="{{ route('contact') }}">Talk to an expert <i class="bi bi-arrow-up-right"></i></a>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="blog-related">
            <div class="container">
                <div class="blog-related__head"><div><span class="section-kicker">Keep reading</span><h2>Related articles</h2></div><a href="{{ route('blog.index') }}">View all <i class="bi bi-arrow-right"></i></a></div>
                <div class="row g-4">
                    @foreach ($relatedPosts as $related)
                        <div class="col-md-4">
                            <article class="blog-card blog-card--compact">
                                <a class="blog-card__media" href="{{ route('blog.show', $related) }}">
                                    @if ($related->featured_image_url)<img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" loading="lazy">@else<span class="blog-card__placeholder"><i class="bi bi-lightning-charge-fill"></i></span>@endif
                                </a>
                                <div class="blog-card__body"><div class="blog-card__meta"><span>{{ ($related->published_at ?? $related->created_at)->format('d M Y') }}</span></div><h3><a href="{{ route('blog.show', $related) }}">{{ $related->title }}</a></h3></div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
