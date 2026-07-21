@extends('admin.layouts.guest')

@section('content')
    <div class="admin-login-form">
        <a href="{{ route('home') }}" class="admin-login-form__logo"><img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}"></a>
        <span class="admin-login-form__eyebrow">Admin portal</span>
        <h2>Welcome back.</h2>
        <p>Sign in to manage the Rocket Batteries website.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="admin-login-control"><i class="bi bi-envelope-fill"></i><input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus></div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="admin-login-control"><i class="bi bi-lock-fill"></i><input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required></div>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Keep me signed in</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 admin-login-submit">Sign in <i class="bi bi-arrow-right"></i></button>
        </form>
        <a href="{{ route('home') }}" class="admin-login-form__back"><i class="bi bi-arrow-left"></i> Back to website</a>
    </div>
@endsection
