@extends('layouts.guest')

@section('title', 'Create Account')

@section('content')
<div class="glass p-8 rounded-2xl shadow-2xl">
    <h2 class="text-2xl font-bold text-white mb-6">Join Techsuite</h2>
    
    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Full Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('name') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
            <input type="password" name="password" id="password" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('password') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
        </div>

        <button type="submit" 
                class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all transform hover:scale-[1.02] active:scale-95">
            Create Account
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-400">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-emerald-400 font-semibold hover:text-emerald-300">Sign in</a>
    </p>
</div>
@endsection
