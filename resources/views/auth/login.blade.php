@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="glass p-8 rounded-2xl shadow-2xl">
    <h2 class="text-2xl font-bold text-white mb-6">Welcome Back</h2>
    
    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-400 mb-2">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
            <input type="password" name="password" id="password" required
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('password') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded bg-white/5 border-white/10 text-blue-500 focus:ring-blue-500/50">
                <span class="ml-2 text-sm text-gray-400">Remember me</span>
            </label>
            <a href="#" class="text-sm text-blue-400 hover:text-blue-300">Forgot password?</a>
        </div>

        <button type="submit" 
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all transform hover:scale-[1.02] active:scale-95">
            Sign In
        </button>
    </form>

    <p class="mt-8 text-center text-sm text-gray-400">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-blue-400 font-semibold hover:text-blue-300">Create one</a>
    </p>
</div>
@endsection
