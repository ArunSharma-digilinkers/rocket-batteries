<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layouts.partials.head')
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="w-100" style="max-width: 400px;">
            @yield('content')
        </div>
    </div>
</body>
</html>
