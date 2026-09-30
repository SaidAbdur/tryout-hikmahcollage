<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - TryoutKu Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased">

@auth('admin')
<div class="flex min-h-screen flex-col md:flex-row">
    <aside class="bg-white p-4 shadow-lg shadow-slate-100 md:sticky md:top-0 md:flex md:h-screen md:w-64 md:shrink-0 md:flex-col">
        <div class="mb-4 flex items-center gap-2 font-display text-2xl font-semibold text-violet-600">
            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-violet-500 text-white"><i data-lucide="graduation-cap" class="h-5 w-5"></i></span>
            TryoutKu Admin
        </div>
        <nav class="flex gap-2 overflow-x-auto md:flex-col">
            @foreach ([['admin.dashboard', 'admin.dashboard', 'layout-dashboard', 'Dashboard'],
                       ['admin.students.index', 'admin.students.*', 'users', 'Students'],
                       ['admin.monitoring', 'admin.monitoring', 'activity', 'Live monitoring']] as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}"
                   class="flex shrink-0 items-center gap-2 rounded-2xl px-4 py-3 font-bold transition {{ request()->routeIs($pattern) ? 'bg-violet-100 text-violet-700' : 'hover:bg-slate-100' }}">
                    <i data-lucide="{{ $icon }}" class="h-5 w-5"></i>{{ $label }}
                </a>
            @endforeach
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-4 md:mt-auto">@csrf
            <button class="btn-danger w-full"><i data-lucide="log-out" class="h-5 w-5"></i> Sign out</button>
        </form>
    </aside>

    <main class="min-w-0 flex-1 p-4 md:p-8">
        @if (session('status'))
            <div class="mb-6 rounded-2xl bg-emerald-100 px-5 py-3 font-bold text-emerald-700">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</div>
@else
<main class="grid min-h-screen place-items-center bg-gradient-to-br from-violet-50 to-sky-50 p-4">
    @yield('content')
</main>
@endauth
</body>
</html>
