<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', config('app.name'))</title>
<meta name="description" content="@yield('meta_description', 'Rocket Batteries India — industrial batteries for UPS, solar, telecom, EV and more.')">

@vite(['resources/sass/app.scss', 'resources/js/app.js'])
@livewireStyles
