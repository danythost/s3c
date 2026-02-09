@extends('layouts.admin')

@section('title', isset($is_admin) ? 'Add New Admin' : 'Add New User')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.users.index') }}" class="glass p-2 rounded-xl text-gray-400 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold">{{ isset($is_admin) ? 'Add New Admin' : 'Add New User' }}</h2>
        </div>
        <p class="text-gray-400 text-sm">{{ isset($is_admin) ? 'Create a new administrator account.' : 'Create a new user account.' }}</p>
    </div>

    <form action="{{ isset($is_admin) ? route('admin.admins.store') : route('admin.users.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="glass rounded-3xl p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full bg-[#0f172a] glass border border-white/10 rounded-2xl px-5 py-3 text-sm outline-none focus:border-blue-500 transition-all"
                           placeholder="John Doe">
                    @error('name') <p class="text-red-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Username -->
                <div class="space-y-2">
                    <label for="username" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required
                           class="w-full bg-[#0f172a] glass border border-white/10 rounded-2xl px-5 py-3 text-sm outline-none focus:border-blue-500 transition-all"
                           placeholder="johndoe">
                    @error('username') <p class="text-red-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full bg-[#0f172a] glass border border-white/10 rounded-2xl px-5 py-3 text-sm outline-none focus:border-blue-500 transition-all"
                       placeholder="john@example.com">
                @error('email') <p class="text-red-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="text-xs font-bold text-gray-400 uppercase tracking-wider">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full bg-[#0f172a] glass border border-white/10 rounded-2xl px-5 py-3 text-sm outline-none focus:border-blue-500 transition-all"
                       placeholder="••••••••">
                @error('password') <p class="text-red-400 text-[10px] font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            @if(!isset($is_admin))
            <!-- Role Selection (Hidden for Admins) -->
            <input type="hidden" name="role" value="user">
            @endif

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-blue-500/20">
                    Create Account
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
