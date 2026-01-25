<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'S3C') }} - @yield('title')</title>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top right, #1a1a2e, #16213e, #0f3460);
            color: #e2e8f0;
        }
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full">
        <nav x-data="{ open: false }" class="glass sticky top-0 z-50 border-b border-white/10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <a href="/" class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">
                            S3C
                        </a>
                        <div class="hidden md:block ml-10">
                            <div class="flex items-baseline space-x-4">
                                <a href="{{ route('home') }}" class="text-gray-300 hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                                <a href="{{ route('vtu.data.index') }}" class="text-gray-300 hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium">Data Bundle</a>
                                <a href="/#about" class="text-gray-300 hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium">About Us</a>
                                <a href="/#contact" class="text-gray-300 hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
                                @guest
                                    <a href="{{ route('login') }}" class="text-gray-300 hover:bg-white/10 px-3 py-2 rounded-md text-sm font-medium transition-all">Login</a>
                                @endguest
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile menu button and Desktop Logout -->
                    <div class="flex items-center gap-4">
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 text-gray-400 hover:text-white transition-colors group">
                                    <span class="text-sm font-medium">Logout</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                </button>
                            </form>
                        @endauth

                        <div class="md:hidden">
                            <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false">
                                <span class="sr-only">Open main menu</span>
                                <!-- Icon when menu is closed -->
                                <svg x-show="!open" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                                <!-- Icon when menu is open -->
                                <svg x-show="open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu, show/hide based on menu state. -->
            <div x-show="open" @click.away="open = false" x-cloak class="md:hidden" id="mobile-menu">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3 bg-[#0f172a]/95 backdrop-blur-lg border-b border-white/10">
                    <a href="{{ route('home') }}" class="text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">Home</a>
                    <a href="{{ route('vtu.data.index') }}" class="text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">Data Bundle</a>
                    <a href="#about" @click="open = false" class="text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">About Us</a>
                    <a href="#contact" @click="open = false" class="text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                    @guest
                        <a href="{{ route('login') }}" class="text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                    @endguest
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-left w-full text-gray-300 hover:bg-white/10 block px-3 py-2 rounded-md text-base font-medium">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </nav>

        <header class="py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-white">@yield('header')</h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl pb-12 px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 glass border-emerald-500/50 bg-emerald-500/10 p-4 rounded-xl text-emerald-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 glass border-red-500/50 bg-red-500/10 p-4 rounded-xl text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
