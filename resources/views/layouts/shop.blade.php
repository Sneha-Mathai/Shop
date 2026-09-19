<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Shop' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">

    <header class="site-header">
        <div class="site-header__inner">
            <a href="{{ route('home') }}" class="brand">
                Shop<span class="brand-dot">.</span>
            </a>

            <nav class="site-nav">
                <a href="{{ route('home') }}" class="nav-link">Products</a>
                <a href="{{ route('cart.index') }}" class="nav-link">
    Cart ({{ collect(session('cart', []))->sum('quantity') }})
</a>

                @auth
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('products.create') }}" class="nav-link">+ Add product</a>
                    @endif

                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-outline">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-outline">Admin login</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="page-main">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>