<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layouts.partials.head')
</head>
<body class="admin-login-body">
    <main class="admin-login-shell">
        <section class="admin-login-brand d-none d-lg-flex">
            <div class="admin-login-brand__content">
                <span class="admin-login-brand__eyebrow"><i></i> Rocket Batteries administration</span>
                <h1>Powerful tools.<br>One secure place.</h1>
                <p>Manage the catalog, customer enquiries, warranties, gallery images, and website content.</p>
                <div class="admin-login-brand__features">
                    <span><i class="bi bi-shield-lock-fill"></i> Secure access</span>
                    <span><i class="bi bi-grid-fill"></i> Centralized management</span>
                </div>
            </div>
            <span class="admin-login-brand__year" aria-hidden="true">1952</span>
        </section>
        <section class="admin-login-panel">
            @yield('content')
        </section>
    </main>
</body>
</html>
