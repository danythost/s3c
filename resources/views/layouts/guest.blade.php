<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'S3C') }} - @yield('title')</title>
    
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
<body class="h-full bg-[#0f172a] relative">
    <!-- Global Announcement Banner -->
    @if(isset($activeAnnouncements) && $activeAnnouncements->count() > 0)
        <div class="fixed top-0 left-0 right-0 z-[100]">
            @foreach($activeAnnouncements as $announcement)
                <div class="bg-yellow-400 text-gray-900 py-2 px-6 text-center border-b border-yellow-500/20 shadow-lg">
                    <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                        <span class="text-lg">📢</span>
                        <div class="text-[11px] font-black uppercase tracking-tight">
                            <span class="mr-2">{{ $announcement->title }}:</span>
                            <span class="font-bold opacity-80">{{ $announcement->message }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="min-h-full flex items-center justify-center w-full p-6">
        <div class="w-full max-w-md mt-16">
            <div class="text-center mb-10">
                <a href="/" class="text-4xl font-bold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent">
                    S3C
                </a>
            </div>
            
            @yield('content')
        </div>
    </div>
</body>
</html>
