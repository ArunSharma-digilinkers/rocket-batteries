<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

@vite(['resources/sass/app.scss', 'resources/js/app.js'])
@livewireStyles
