<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hikmah College - Tryout System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #2563EB;
            background-image: url('data:image/svg+xml,%3Csvg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"%3E%3Cpath d="M0 0h40v40H0V0zm20 20h20v20H20V20zM0 20h20v20H0V20z" fill="%23ffffff" fill-opacity="0.05" fill-rule="evenodd"/%3E%3C/svg%3E');
        }
        
        /* Floating animations & effects */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col text-gray-800">

    <!-- Header for authenticated user in tryout, otherwise can be overridden or hidden -->
    @if(!isset($hideHeader) || !$hideHeader)
    <header class="bg-white/10 backdrop-blur-md text-white py-4 px-6 shadow-sm border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-hikmah-neon rounded-xl flex items-center justify-center text-hikmah-bluedark font-bold text-xl shadow-lg">HC</div>
                <h1 class="text-xl font-extrabold tracking-tight">HIKMAH <span class="text-hikmah-neon">COLLEGE</span></h1>
            </div>
            @auth('student')
            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col items-end">
                    <span class="font-bold text-sm leading-tight">{{ Auth::guard('student')->user()->name }}</span>
                    <span class="text-xs text-blue-200">{{ Auth::guard('student')->user()->student_id }}</span>
                </div>
                <div class="w-10 h-10 rounded-full bg-white text-hikmah-blue flex items-center justify-center font-bold">
                    {{ substr(Auth::guard('student')->user()->name, 0, 1) }}
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="bg-red-500/80 hover:bg-red-500 text-white p-2 rounded-lg text-sm font-semibold transition shadow hover:shadow-md" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>
    @endif

    <!-- Main Content -->
    <main class="flex-grow flex flex-col w-full relative">
        @yield('content')
    </main>

    <!-- Footer -->
    @if(!isset($hideFooter) || !$hideFooter)
    <footer class="text-center py-6 text-blue-100 text-sm mt-auto border-t border-white/10 bg-hikmah-blue/80 backdrop-blur-sm z-10">
        <p class="font-medium">&copy; {{ date('Y') }} Hikmah College CBT System. All rights reserved.</p>
        <p class="text-xs text-blue-300 mt-1">Membangun Masa Depan Berprestasi</p>
    </footer>
    @endif
</body>
</html>
