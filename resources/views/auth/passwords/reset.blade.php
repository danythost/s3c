@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="glass p-10 rounded-[3rem] border-white/5 shadow-2xl relative overflow-hidden group">
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/15 transition-all duration-500"></div>
    
    <div class="relative">
        <div class="mb-10 space-y-2">
            <h2 class="text-3xl font-black text-white tracking-tighter">New Security Key</h2>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Update your authentication credentials</p>
        </div>
        
        <form action="{{ route('password.update') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="space-y-6">
                <div>
                    <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required readonly
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white/50 font-bold placeholder-gray-600 focus:outline-none transition-all cursor-not-allowed">
                    @error('email') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">New Password</label>
                    <input type="password" name="password" id="password" required autofocus
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    @error('password') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-95">
                UPDATE SECURITY KEY
            </button>
        </form>
    </div>
</div>
@endsection
