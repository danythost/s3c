<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>S3C Admin - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .sidebar-link.active {
            background: rgba(37, 99, 235, 0.1);
            border-left: 4px solid #2563eb;
            color: #fff;
        }
    </style>
</head>
<body class="h-full overflow-hidden">
    <div class="flex h-full" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 glass lg:static lg:translate-x-0 transition-transform duration-300 ease-in-out border-r border-white/10">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="p-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">
                        S3C ADMIN
                    </a>
                </div>

                <!-- Nav Links -->
                <nav class="flex-1 px-4 space-y-2 py-4">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.vtu.plans') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.vtu.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>VTU Services</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Users</span>
                    </a>
                </nav>

                <!-- Footer -->
                <div class="p-4 border-t border-white/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-400/10 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Navbar -->
            <header class="glass h-16 flex items-center justify-between px-6 border-b border-white/10">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-400">Welcome, </span>
                    <span class="text-sm font-bold text-white">{{ auth()->user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-blue-500/20 border border-blue-500/50 flex items-center justify-center text-blue-400">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 md:p-10">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 glass border-emerald-500/50 bg-emerald-500/10 p-4 rounded-xl text-emerald-400 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 glass border-red-500/50 bg-red-500/10 p-4 rounded-xl text-red-400 flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
