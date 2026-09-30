<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tryout online') - TryoutKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-violet-50 via-sky-50 to-amber-50 bg-fixed font-sans text-slate-700 antialiased">

@hasSection('focus') @else
<header class="sticky top-0 z-30 border-b border-white/60 bg-white/70 backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3">
        <a href="{{ auth('student')->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2 font-display text-2xl font-semibold text-violet-600">
            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-violet-500 text-white"><i data-lucide="rocket" class="h-5 w-5"></i></span>
            TryoutKu
        </a>
        <nav class="flex items-center gap-1 text-sm font-bold sm:gap-2">
            @auth('student')
                <a href="{{ route('dashboard') }}" class="rounded-xl px-3 py-2 transition hover:bg-violet-100 {{ request()->routeIs('dashboard') ? 'bg-violet-100 text-violet-700' : '' }}">Beranda</a>
                <a href="{{ route('history') }}" class="rounded-xl px-3 py-2 transition hover:bg-violet-100 {{ request()->routeIs('history') ? 'bg-violet-100 text-violet-700' : '' }}">Riwayat</a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="cursor-pointer rounded-xl px-3 py-2 text-rose-500 transition hover:bg-rose-50">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-xl px-3 py-2 transition hover:bg-violet-100">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
            @endauth
        </nav>
    </div>
</header>
@endif

<main class="mx-auto max-w-6xl px-4 py-8">
    @if (session('status'))
        <div class="mb-6 rounded-2xl bg-emerald-100 px-5 py-3 font-bold text-emerald-700">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-2xl bg-rose-100 px-5 py-3 font-bold text-rose-600">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>
</body>
</html>
