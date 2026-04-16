<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S3C Core System - API Backend</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #f8fafc;
        }
        .pulse-orb {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .5; transform: scale(1.05); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-900/50 backdrop-blur-xl border border-slate-700 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500 rounded-full blur-3xl opacity-20 pulse-orb"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-emerald-500 rounded-full blur-3xl opacity-20 pulse-orb" style="animation-delay: 2s"></div>
        
        <div class="relative z-10 text-center">
            <div class="inline-block p-4 rounded-2xl bg-slate-800 border border-slate-700 mb-6">
                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-black mb-2 tracking-tight">S3C Core API</h1>
            <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                This environment operates strictly as the securely optimized backend and API service for the S3C application. 
            </p>
            
            <div class="flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-widest text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                System Operational
            </div>
        </div>
    </div>
</body>
</html>
