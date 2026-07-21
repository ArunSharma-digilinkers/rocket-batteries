@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->name . ' Articles' : 'Blog') . ' — ' . config('app.name'))
@section('meta_description', 'Insights, battery care guides, industry updates, and energy solutions from Rocket Batteries.')

@section('content')
    <x-page-header
        :title="$activeCategory ? $activeCategory->name : 'Insights & Ideas'"
        subtitle="Practical battery knowledge, company updates, and perspectives on the future of energy."
        :items="$activeCategory ? ['Blog' => route('blog.index'), $activeCategory->name => null] : ['Blog' => null]"
    />

    <section class="blog-index">
        <div class="container">
            <div class="blog-index__intro">
                <div>
                    <span class="section-kicker">Rocket Knowledge Hub</span>
                    <h2>Stay informed. Stay powered.</h2>
                </div>
                <p>Explore useful advice, emerging technology, and stories from the world of dependable energy.</p>
            </div>

            @if ($categories->isNotEmpty())
                <nav class="blog-filters" aria-label="Blog categories">
                    <a class="{{ ! $activeCategory ? 'active' : '' }}" href="{{ route('blog.index') }}">All articles</a>
                    @foreach ($categories as $category)
                        <a class="{{ $activeCategory?->is($category) ? 'active' : '' }}" href="{{ route('blog.index', ['category' => $category->slug]) }}">
                            {{ $category->name }} <span>{{ $category->posts_count }}</span>
                        </a>
                    @endforeach
                </nav>
            @endif

            @if ($posts->isNotEmpty())
                <div class="row g-4 blog-grid">
                    @foreach ($posts as $post)
                        <div class="col-md-6 col-xl-4">
                            <article class="blog-card">
                                <a class="blog-card__media" href="{{ route('blog.show', $post) }}" aria-label="Read {{ $post->title }}">
                                    @if ($post->featured_image_url)
                                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                                    @else
                                        <span class="blog-card__placeholder"><i class="bi bi-lightning-charge-fill"></i></span>
                                    @endif
                                    @if ($post->category)<span class="blog-card__category">{{ $post->category->name }}</span>@endif
                                </a>
                                <div class="blog-card__body">
                                    <div class="blog-card__meta">
                                        <span><i class="bi bi-calendar3"></i> {{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</span>
                                        @if ($post->author)<span><i class="bi bi-person"></i> {{ $post->author }}</span>@endif
                                    </div>
                                    <h3><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
                                    <p>{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 145) }}</p>
                                    <a class="blog-card__link" href="{{ route('blog.show', $post) }}">Read article <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="blog-pagination">{{ $posts->links() }}</div>
            @else
                <div class="blog-empty">
                    <i class="bi bi-journal-text"></i>
                    <h3>No articles found</h3>
                    <p>{{ $activeCategory ? 'There are no published articles in this category yet.' : 'Our first article will be published soon.' }}</p>
                    @if ($activeCategory)<a href="{{ route('blog.index') }}" class="btn btn-primary">View all articles</a>@endif
                </div>
            @endif
        </div>
    </section>
@endsection
