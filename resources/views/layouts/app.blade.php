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
        select option {
            background-color: #1a1a2e;
            color: #e2e8f0;
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
                                            $isMarketing = request()->routeIs('home', 'shop') || request()->is('/');
                                            
                                            $appLinks = [
                                                ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                                                ['name' => 'Data Bundle', 'route' => 'vtu.data.index', 'icon' => '<path d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                                                ['name' => 'Airtime', 'route' => 'vtu.airtime.index', 'icon' => '<path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>'],
                                                ['name' => 'My Wallet', 'route' => 'wallet.index', 'icon' => '<path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
                                                ['name' => 'Convert to Cash', 'url' => '#', 'icon' => '<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                                            ];

                                            $marketingLinks = [
                                                ['name' => 'Shop', 'route' => 'shop', 'icon' => '<path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>'],
                                                ['name' => 'About Us', 'url' => '#about', 'icon' => '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                                ['name' => 'Convert to Cash', 'url' => '#', 'icon' => '<path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                                                ['name' => 'Developers', 'url' => '#', 'icon' => '<path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>'],
                                                ['name' => 'Contact', 'url' => '#contact', 'icon' => '<path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                                            ];

                                            $activeLinks = $isMarketing ? $marketingLinks : $appLinks;
                                        @endphp
                                        
                                        @foreach($activeLinks as $link)
                                            <li>
                                                <a href="{{ isset($link['route']) ? route($link['route']) : $link['url'] }}" 
                                                   class="{{ (isset($link['route']) && request()->routeIs($link['route'])) ? 'bg-white/5 text-blue-400 border-l-2 border-blue-400' : 'text-gray-400 hover:text-white hover:bg-white/5' }} flex items-center gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold transition-all">
                                                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        {!! $link['icon'] !!}
                                                    </svg>
                                                    {{ $link['name'] }}
                                                </a>
                                            </li>
                                        @endforeach

                                        @if($isMarketing)
                                            @guest
                                                <li class="mt-4 pt-4 border-t border-white/5">
                                                    <a href="{{ route('login') }}" class="flex items-center gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-blue-400 hover:bg-blue-500/10 transition-all">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                                        Sign In
                                                    </a>
                                                </li>
                                            @endguest
                                            @auth
                                                <li class="mt-4 pt-4 border-t border-white/5">
                                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-x-3 rounded-md p-3 text-sm leading-6 font-semibold text-emerald-400 hover:bg-emerald-500/10 transition-all">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699-2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                                                        Go to Dashboard
                                                    </a>
                                                </li>
                                            @endauth
                                        @endif
                                    </ul>
                                </li>

                                @if(!$isMarketing)
                                    <li class="mt-auto pt-4 border-t border-white/5">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="-mx-2 flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-red-500/10 hover:text-red-400 w-full text-left transition-all">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li class="mt-auto pt-4 border-t border-white/5">
                                        <div class="flex flex-col gap-2 p-2">
                                            <a href="#" class="text-[10px] uppercase font-black tracking-widest text-gray-500 hover:text-blue-400 transition-colors">Privacy Policy</a>
                                            <a href="#" class="text-[10px] uppercase font-black tracking-widest text-gray-500 hover:text-blue-400 transition-colors">Terms of Service</a>
                                            <div class="flex items-center gap-2 mt-2">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                                <span class="text-[9px] text-gray-600 font-bold uppercase">System Operational</span>
                                            </div>
                                        </div>
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
                                           class="{{ (isset($link['route']) && request()->routeIs($link['route'])) ? 'bg-blue-500/10 text-blue-400 border-l-4 border-blue-500' : 'text-gray-400 hover:text-white hover:bg-white/5' }} group flex items-center gap-x-3 rounded-r-xl p-3 text-sm leading-6 font-bold transition-all">
                                             <svg class="h-6 w-6 shrink-0 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                 {!! $link['icon'] !!}
                                             </svg>
                                             {{ $link['name'] }}
                                         </a>
                                    </li>
                                @endforeach

                                @if($isMarketing)
                                    @guest
                                        <li class="mt-4 pt-4 border-t border-white/5">
                                            <a href="{{ route('login') }}" class="flex items-center gap-x-3 rounded-md p-3 text-sm leading-6 font-bold text-blue-400 hover:bg-blue-500/10 transition-all">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                                Sign In
                                            </a>
                                        </li>
                                    @endguest
                                    @auth
                                        <li class="mt-4 pt-4 border-t border-white/5">
                                            <a href="{{ route('dashboard') }}" class="flex items-center gap-x-3 rounded-md p-3 text-sm leading-6 font-bold text-emerald-400 hover:bg-emerald-500/10 transition-all">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699-2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /></svg>
                                                Go to Dashboard
                                            </a>
                                        </li>
                                    @endauth
                                @endif
                            </ul>
                        </li>
                        
                        @if(!$isMarketing)
                            <li class="mt-auto pt-6 border-t border-white/5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="-mx-2 flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-red-500/10 hover:text-red-400 w-full text-left transition-all">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        @else
                            <li class="mt-auto pt-6 border-t border-white/5">
                                <div class="flex flex-col gap-3 p-2">
                                    <a href="#" class="text-[10px] uppercase font-black tracking-widest text-gray-500 hover:text-blue-400 transition-colors">Privacy Policy</a>
                                    <a href="#" class="text-[10px] uppercase font-black tracking-widest text-gray-500 hover:text-blue-400 transition-colors">Terms of Service</a>
                                    <div class="flex items-center gap-2 mt-4">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Cloud Infrastructure Active</span>
                                    </div>
                                </div>
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
