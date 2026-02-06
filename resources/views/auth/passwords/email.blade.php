@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="glass p-10 rounded-[3rem] border-white/5 shadow-2xl relative overflow-hidden group">
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/15 transition-all duration-500"></div>
    
    <div class="relative">
        <div class="mb-10 space-y-2">
            <h2 class="text-3xl font-black text-white tracking-tighter">Reset Security</h2>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Forgot your key? Let's recover it.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold">
                {{ session('status') }}
            </div>
        @endif
        
        <form action="{{ route('password.email') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="email" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    @error('email') <p class="mt-2 text-xs text-red-400 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-95">
                SEND RESET LINK
            </button>
        </form>

        <p class="mt-12 text-center text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">
            Remembered? 
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-white transition-colors ml-2">Back to Login</a>
        </p>
    </div>
</div>
@endsection
