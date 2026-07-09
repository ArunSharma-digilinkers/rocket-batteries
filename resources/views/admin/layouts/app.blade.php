<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layouts.partials.head')
</head>
<body>
    @include('admin.layouts.partials.topbar')

    <div class="container-fluid">
        <div class="row">
            @include('admin.layouts.partials.sidebar')

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h3">@yield('title')</h1>
                    <div>@yield('actions')</div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @include('admin.layouts.partials.scripts')
</body>
</html>
