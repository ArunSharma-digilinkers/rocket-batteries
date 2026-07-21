@props(['items' => []])

<nav aria-label="breadcrumb" class="container pt-4">
    <ol class="breadcrumb modern-breadcrumb-light mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a></li>
        @foreach ($items as $label => $url)
            @if ($loop->last || ! $url)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
