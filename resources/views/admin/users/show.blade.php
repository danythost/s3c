@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="glass p-2 rounded-xl text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold">User Profile</h2>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
        </div>
    </div>

    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this user\'s status?')">
        @csrf
        @method('PATCH')
        <button type="submit" class="px-6 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 {{ $user->is_active ? 'bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white' }}">
            @if($user->is_active)
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                Suspend User
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Activate User
            @endif
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="space-y-6">
        <div class="glass p-6 rounded-2xl text-center">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 mx-auto flex items-center justify-center text-3xl font-bold text-white mb-4">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400 mb-4">{{ $user->email }}</p>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 text-xs font-bold text-gray-300">
                <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                {{ $user->is_active ? 'Active Account' : 'Suspended' }}
            </div>
        </div>

        <div class="glass p-6 rounded-2xl">
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Wallet Balance</h4>
            <h2 class="text-3xl font-bold text-white mb-1">₦{{ number_format($user->wallet->balance ?? 0, 2) }}</h2>
            <p class="text-xs text-gray-500">Available Funds</p>
        </div>

        <div class="glass p-6 rounded-2xl">
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Account Info</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Role</span>
                    <span class="text-white capitalize">{{ $user->role ?? 'User' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Joined</span>
                    <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last Login</span>
                    <span class="text-white">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-6">Recent Transactions</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 border-b border-white/10">
                            <th class="pb-3 pl-2">Reference</th>
                            <th class="pb-3">Service</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($user->orders as $order)
                            <tr class="text-sm group hover:bg-white/5 transition-colors">
                                <td class="py-3 pl-2 font-mono text-blue-400">
                                    <a href="{{ route('admin.orders.show', $order) }}">{{ substr($order->reference, 0, 8) }}...</a>
                                </td>
                                <td class="py-3 uppercase text-xs">{{ $order->type }}</td>
                                <td class="py-3 font-bold">₦{{ number_format($order->amount, 2) }}</td>
                                <td class="py-3">
                                    <span class="text-[10px] uppercase font-bold {{ $order->status === 'success' ? 'text-emerald-400' : ($order->status === 'failed' ? 'text-red-400' : 'text-amber-400') }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-400 text-xs">{{ $order->created_at->format('M d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 text-xs">No recent transactions.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Login Logs (Mock) -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4">Login Activity</h3>
            @if($user->last_login_at)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white">Last Successful Login</p>
                        <p class="text-xs text-gray-400">{{ $user->last_login_at->format('l, F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm">No login history available.</p>
            @endif
        </div>
    </div>
</div>
@endsection
