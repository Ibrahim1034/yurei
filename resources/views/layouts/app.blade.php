
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'YUREI Kenya') }} - @yield('title', 'Youth Rescue and Empowerment Initiative')</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="@yield('meta-description', 'Youth Rescue and Empowerment Initiative - Empowering youth in Kenya through education, health, entrepreneurship, and community development.')">
        <meta name="keywords" content="@yield('meta-keywords', 'YUREI Kenya, youth empowerment, education, health, entrepreneurship, community development, Kenya youth programs')">
        <meta name="author" content="YUREI Kenya">
        
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="@yield('og-title', config('app.name', 'YUREI Kenya'))">
        <meta property="og:description" content="@yield('og-description', 'Youth Rescue and Empowerment Initiative - Empowering youth in Kenya through education, health, entrepreneurship, and community development.')">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="@yield('og-image', asset('images/og-image.jpg'))">
        <meta property="og:site_name" content="YUREI Kenya">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="@yield('twitter-title', config('app.name', 'YUREI Kenya'))">
        <meta name="twitter:description" content="@yield('twitter-description', 'Youth Rescue and Empowerment Initiative - Empowering youth in Kenya')">
        <meta name="twitter:image" content="@yield('twitter-image', asset('images/twitter-image.jpg'))">

      <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/web_pics/YUREI_036.ico') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/web_pics/YUREI_036.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto:100,300,400,500,700,900|poppins:100,200,300,400,500,600,700,800,900|raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet" />

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

        <!-- Additional Stylesheets -->
        @stack('styles')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Analytics Script (Optional) -->
        @includeWhen(config('app.analytics.enabled'), 'partials.analytics')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">

            <!-- Page Heading -->
            <header class="navbar-dashboard shadow-sm sticky-top" role="banner">
                <div class="container py-3">
                    <div class="d-flex justify-content-between align-items-center">
                       <!-- Logo and Brand -->
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/web_pics/yurei-036.jpeg') }}" 
                                alt="YUREI Kenya Logo" 
                                class="me-3"
                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                            <div>
                                @auth
                                    <a href="{{ Auth::user()->role === 1 ? route('admin.dashboard') : route('dashboard') }}" style="text-decoration: none;">
                                @else
                                    <a href="{{ route('dashboard') }}" style="text-decoration: none;">
                                @endauth
                                        <h4 class="fw-bold mb-0 text-primary">Dashboard</h4>
                                        <small class="text-muted">YUREI</small>
                                    </a>
                            </div>
                        </div>
                        
                        <!-- User Profile Dropdown -->
                        @auth
                        <div class="dropdown">
                            <button class="btn btn-link text-decoration-none dropdown-toggle d-flex align-items-center" 
                                    type="button" 
                                    id="userDropdown" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                <img src="{{ Auth::user()->profile_picture_url }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="rounded-circle me-2"
                                     style="width: 32px; height: 32px; object-fit: cover;">
                                <span class="text-dark">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                              
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main id="main-content" role="main">
                {{ $slot }}
            </main>

        </div>

        <!-- Additional Scripts -->
        @stack('scripts')

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050; max-width: 400px;" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050; max-width: 400px;" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </body>
</html>
