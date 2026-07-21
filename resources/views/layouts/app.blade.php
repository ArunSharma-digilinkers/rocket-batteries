<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <a class="visually-hidden-focusable" href="#main-content">Skip to main content</a>

    @include('layouts.partials.topbar')
    @include('layouts.partials.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    @include('layouts.partials.whatsapp')
    @include('layouts.partials.scripts')
</body>
</html>
