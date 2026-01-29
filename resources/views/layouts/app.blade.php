<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'S3C') }} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
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
<body class="h-full bg-[#0f172a] text-gray-200">
    <div x-data="{ sidebarOpen: false }" class="min-h-full">
        <!-- Sidebar for Mobile -->
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>
            <div @click.away="sidebarOpen = false" class="fixed inset-0 flex">
                <div class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-[#1a1a2e] px-6 pb-4 border-r border-white/5">
                        <div class="flex h-16 shrink-0 items-center justify-between">
                            <a href="/" class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">S3C</a>
                            <button @click="sidebarOpen = false" class="-m-2.5 p-2.5 text-gray-400">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                <li>
                                    <ul role="list" class="-mx-2 space-y-1">
                                        @php
                                            $isMarketing = request()->routeIs('home') || request()->is('/');
                                            
                                            $appLinks = [
                                                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '🏠'],
                                                ['name' => 'Data Bundle', 'route' => 'vtu.data.index', 'icon' => '📶'],
                                                ['name' => 'Airtime', 'route' => 'vtu.airtime.index', 'icon' => '📞'],
                                                ['name' => 'My Wallet', 'route' => 'wallet.index', 'icon' => '💳'],
                                            ];

                                            $marketingLinks = [
                                                ['name' => 'Shop', 'route' => 'shop', 'icon' => '🛍️'],
                                                ['name' => 'About Us', 'url' => '#about', 'icon' => '📖'],
                                                ['name' => 'Developers', 'url' => '#', 'icon' => '👨‍💻'],
                                                ['name' => 'Contact', 'url' => '#contact', 'icon' => '✉️'],
                                            ];

                                            $activeLinks = $isMarketing ? $marketingLinks : $appLinks;
                                        @endphp
                                        
                                        @foreach($activeLinks as $link)
                                            <li>
                                                <a href="{{ isset($link['route']) ? route($link['route']) : $link['url'] }}" 
                                                   class="{{ (isset($link['route']) && request()->routeIs($link['route'])) ? 'bg-white/5 text-blue-400 border-l-2 border-blue-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }} flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold transition-all">
                                                    <span>{{ $link['icon'] }}</span>
                                                    {{ $link['name'] }}
                                                </a>
                                            </li>
                                        @endforeach

                                        @if($isMarketing)
                                            @guest
                                                <li class="mt-4 pt-4 border-t border-white/5">
                                                    <a href="{{ route('login') }}" class="flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-blue-400 hover:bg-blue-500/10 transition-all">
                                                        <span>👤</span>
                                                        Sign In
                                                    </a>
                                                </li>
                                            @endguest
                                            @auth
                                                <li class="mt-4 pt-4 border-t border-white/5">
                                                    <a href="{{ route('dashboard') }}" class="flex gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-emerald-400 hover:bg-emerald-500/10 transition-all">
                                                        <span>🚀</span>
                                                        Go to Dashboard
                                                    </a>
                                                </li>
                                            @endauth
                                        @endif
                                    </ul>
                                </li>

                                @if(!$isMarketing)
                                    <li class="mt-auto">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="-mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-red-500/10 hover:text-red-400 w-full text-left transition-all">
                                                <span>🚪</span>
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Static Sidebar for Desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-[#1a1a2e]/50 backdrop-blur-xl border-r border-white/5 px-6 pb-4">
                <div class="flex h-20 shrink-0 items-center">
                    <a href="/" class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">S3C</a>
                </div>
                <nav class="flex flex-1 flex-col">
                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                        <li>
                            <ul role="list" class="-mx-2 space-y-2 mt-4">
                                @foreach($activeLinks as $link)
                                    <li>
                                        <a href="{{ isset($link['route']) ? route($link['route']) : $link['url'] }}" 
                                           class="{{ (isset($link['route']) && request()->routeIs($link['route'])) ? 'bg-blue-500/10 text-blue-400 border-l-4 border-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }} group flex gap-x-3 rounded-r-xl p-3 text-sm leading-6 font-bold transition-all">
                                            <span class="text-xl opacity-70 group-hover:scale-110 transition-transform">{{ $link['icon'] }}</span>
                                            {{ $link['name'] }}
                                        </a>
                                    </li>
                                @endforeach

                                @if($isMarketing)
                                    @guest
                                        <li class="mt-4 pt-4 border-t border-white/5">
                                            <a href="{{ route('login') }}" class="flex gap-x-3 rounded-md p-3 text-sm leading-6 font-bold text-blue-400 hover:bg-blue-500/10 transition-all">
                                                <span>👤</span>
                                                Sign In
                                            </a>
                                        </li>
                                    @endguest
                                    @auth
                                        <li class="mt-4 pt-4 border-t border-white/5">
                                            <a href="{{ route('dashboard') }}" class="flex gap-x-3 rounded-md p-3 text-sm leading-6 font-bold text-emerald-400 hover:bg-emerald-500/10 transition-all">
                                                <span>🚀</span>
                                                Go to Dashboard
                                            </a>
                                        </li>
                                    @endauth
                                @endif
                            </ul>
                        </li>
                        
                        @if(!$isMarketing)
                            <li class="mt-auto">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="-mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-red-500/10 hover:text-red-400 w-full text-left transition-all">
                                        <span>🚪</span>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main Content Wrapper -->
        <div class="lg:pl-72 flex flex-col min-h-screen">
            <!-- Topbar -->
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-white/5 bg-[#0f172a]/80 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button @click="sidebarOpen = true" type="button" class="-m-2.5 p-2.5 text-gray-400 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-white/5 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 justify-between items-center">
                    <div class="text-sm font-medium text-gray-400">
                        @yield('header')
                    </div>
                    
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        @auth
                            <div class="flex flex-col items-end">
                                <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">{{ auth()->user()->role }}</span>
                                <span class="text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-emerald-500 p-[1px]">
                                <div class="h-full w-full rounded-[10px] bg-[#1a1a2e] flex items-center justify-center font-bold text-white uppercase">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <main class="py-10 flex-1">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div class="mb-8 glass border-emerald-500/50 bg-emerald-500/10 p-4 rounded-xl text-emerald-400 flex items-center gap-3">
                            <span class="text-xl">✅</span>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-8 glass border-red-500/50 bg-red-500/10 p-4 rounded-xl text-red-400 flex items-center gap-3">
                            <span class="text-xl">❌</span>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
            
            <footer class="py-6 border-t border-white/5 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] text-gray-500 uppercase font-bold tracking-widest">
                    <span>© {{ date('Y') }} {{ config('app.name') }}</span>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-blue-400">Support</a>
                        <a href="#" class="hover:text-blue-400">Terms</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>
