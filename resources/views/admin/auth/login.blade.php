@extends('layouts.guest')

@section('title', 'Admin Login')

@section('content')
<div class="glass p-10 rounded-[3rem] border-white/5 shadow-2xl relative overflow-hidden group">
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-red-500/10 rounded-full blur-3xl group-hover:bg-red-500/15 transition-all duration-500"></div>
    
    <div class="relative">
        <div class="mb-10 space-y-2">
            <h2 class="text-3xl font-black text-white tracking-tighter">Admin Portal</h2>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Secure Administrative Access</p>
        </div>
        
        <form action="{{ route('admin.login') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all">
                    @error('email') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Security Key</label>
                    <input type="password" name="password" id="password" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all">
                    @error('password') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center cursor-pointer group/check">
                    <input type="checkbox" name="remember" class="w-5 h-5 rounded-lg bg-white/5 border-white/10 text-red-500 focus:ring-red-500/50 transition-all">
                    <span class="ml-3 text-xs font-bold text-gray-500 group-hover/check:text-gray-300 transition-colors">Remember device</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-red-500/20 transition-all transform hover:scale-[1.02] active:scale-95">
                AUTHORIZE ADMIN ACCESS
            </button>
        </form>
    </div>
</div>
@endsection
