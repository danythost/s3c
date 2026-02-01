@extends('layouts.guest')

@section('title', 'Create Account')

@section('content')
<div class="glass p-10 rounded-[3rem] border-white/5 shadow-2xl relative overflow-hidden group">
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/15 transition-all duration-500"></div>
    
    <div class="relative">
        <div class="mb-10 space-y-2">
            <h2 class="text-3xl font-black text-white tracking-tighter">Join S3C</h2>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Create your digital wallet today</p>
        </div>
        
        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                @error('name') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="username" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                @error('username') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                @error('email') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                    @error('password') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Confirm</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-emerald-500/20 transition-all transform hover:scale-[1.02] active:scale-95 mt-4">
                LEGALIZE ACCOUNT
            </button>
        </form>

        <p class="mt-12 text-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">
            Already registered? 
            <a href="{{ route('login') }}" class="text-emerald-400 hover:text-white transition-colors ml-2">Sign In</a>
        </p>
    </div>
</div>
@endsection
