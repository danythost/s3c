@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="glass p-10 rounded-[3rem] border-white/5 shadow-2xl relative overflow-hidden group">
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/15 transition-all duration-500"></div>
    
    <div class="relative">
        <div class="mb-10 space-y-2">
            <h2 class="text-3xl font-black text-white tracking-tighter">Welcome Back</h2>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Access your digital dashboard</p>
        </div>
        
        <form action="{{ route('login') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="username" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    @error('username') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Security Key</label>
                    <input type="password" name="password" id="password" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    @error('password') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer group/check">
                    <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg bg-white/5 border-white/10 text-blue-500 focus:ring-blue-500/50 transition-all">
                    <span class="ml-3 text-xs font-bold text-gray-500 group-hover/check:text-gray-300 transition-colors">Remember device</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-95">
                AUTHORIZE SIGN IN
            </button>
        </form>

        <p class="mt-12 text-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">
            New to the platform? 
            <a href="{{ route('register') }}" class="text-blue-400 hover:text-white transition-colors ml-2">Open Account</a>
        </p>
    </div>
</div>
@endsection
