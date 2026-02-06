@extends('layouts.app')

@section('title', 'My Profile')

@section('header', 'Profile Overview')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="glass p-2 rounded-xl text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold">My Profile</h2>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
        </div>
    </div>
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
            <div class="flex items-end justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-1">₦{{ number_format($user->wallet->balance ?? 0, 2) }}</h2>
                    <p class="text-xs text-gray-500">Available Funds</p>
                </div>
                <a href="{{ route('wallet.index') }}" class="text-xs font-bold text-blue-400 hover:text-blue-300 transition-colors uppercase tracking-wider">Add Funds →</a>
            </div>
        </div>

        <div class="glass p-6 rounded-2xl">
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Account Information</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Username</span>
                    <span class="text-white">{{ $user->username }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Account Tier</span>
                    <span class="text-white capitalize">{{ $user->tier ?? 'Basic' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Member Since</span>
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
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold">Recent Wallet Activity</h3>
                <a href="{{ route('wallet.index') }}" class="text-xs font-bold text-gray-400 hover:text-white transition-colors uppercase tracking-wider">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 border-b border-white/10">
                            <th class="pb-3 pl-2">Reference</th>
                            <th class="pb-3">Type</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($user->transactions as $transaction)
                            <tr class="text-sm group hover:bg-white/5 transition-colors">
                                <td class="py-3 pl-2 font-mono text-blue-400">
                                    {{ substr($transaction->reference, 0, 8) }}...
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold {{ $transaction->type === 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ $transaction->type }}
                                    </span>
                                </td>
                                <td class="py-3 font-bold">₦{{ number_format($transaction->amount, 2) }}</td>
                                <td class="py-3">
                                    <span class="text-[10px] uppercase font-bold {{ $transaction->status === 'success' || $transaction->status === 'completed' ? 'text-emerald-400' : ($transaction->status === 'failed' ? 'text-red-400' : 'text-amber-400') }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-400 text-xs">{{ $transaction->created_at->format('M d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 text-xs">No recent wallet activity.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Login Logs -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4">Security Activity</h3>
            @if($user->last_login_at)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
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
