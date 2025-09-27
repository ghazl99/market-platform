<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Mobile optimizations -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="كوانتم ماركت">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#059669">
    <meta name="msapplication-TileColor" content="#059669">
    <meta name="msapplication-config" content="browserconfig.xml">

    <!-- Icons -->
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/icon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/icon-16x16.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles-store.css') }}?v={{ time() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">

    @stack('styles')
</head>

<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store me-2"></i>
                {{ $store->name }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    {{-- dashboard --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            {{ __('Dashboard') }}
                        </a>
                    </li>

                    {{-- staffs --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.staff.index') ? 'active' : '' }}"
                            href="{{ route('dashboard.staff.index') }}">
                            <i class="fas fa-users me-1"></i>
                            {{ __('Staffs') }}
                        </a>
                    </li>

                    {{-- categories --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.category.index') ? 'active' : '' }}"
                            href="{{ route('dashboard.category.index') }}">
                            <i class="fas fa-box me-1"></i>
                            {{ __('Categories') }}
                        </a>
                    </li>

                    {{-- products --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.product.index') ? 'active' : '' }}"
                            href="{{ route('dashboard.product.index') }}">
                            <i class="fas fa-box me-1"></i>
                            {{ __('Products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.order.index') || request()->routeIs('dashboard.order.show') ? 'active' : '' }}"
                            href="{{ route('dashboard.order.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>
                            {{ __('Orders') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.customer.index') ? 'active' : '' }}"
                            href="{{ route('dashboard.customer.index') }}">
                            <i class="fas fa-users me-1"></i>
                            {{ __('Customers') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="$">
                            <i class="fas fa-chart-bar me-1"></i>
                            التقارير
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#">
                            <i class="fas fa-cog me-1"></i>
                            الإعدادات
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center ms-2">
                        <select class="form-select w-auto" onchange="window.location.href=this.value">
                            <option value="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                                {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>
                                AR - العربية
                            </option>
                            <option value="{{ LaravelLocalization::getLocalizedURL('en') }}"
                                {{ app()->getLocale() === 'en' ? 'selected' : '' }}>
                                EN - English
                            </option>
                        </select>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ $store->store_url }}" target="_blank">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    {{ __('Visit Store') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('stores.index') }}">
                                    <i class="fas fa-list me-2"></i>
                                    {{ __('My Stores') }}

                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h6>لوحة تحكم {{ $store->name }}</h6>
                    <p class="mb-0">جميع الحقوق محفوظة &copy; {{ date('Y') }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="#" class="btn btn-outline-light btn-sm" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>
                        عرض المتجر
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
