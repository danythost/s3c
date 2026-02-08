@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@if(isset($stats['provider_balance']) && $stats['provider_balance'] < 5000)
<div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <h4 class="font-bold">Low Wallet Balance Alert</h4>
            <p class="text-xs opacity-80">Provider wallet is running low (₦{{ number_format($stats['provider_balance'], 2) }}). Please top up immediately.</p>
        </div>
    </div>
    <a href="#" class="px-4 py-2 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition-colors">Top Up</a>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- User Wallets -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center text-violet-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">User Wallets</p>
                <h3 class="text-2xl font-bold text-white">₦{{ number_format($stats['total_wallets_balance'], 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Today's Transactions -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center text-pink-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Today's Txns</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['today_transactions'] }}</h3>
                <p class="text-xs text-emerald-400">+₦{{ number_format($stats['today_revenue']) }}</p>
            </div>
        </div>
    </div>

    <!-- Provider Status -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl {{ isset($stats['provider_balance']) ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }} flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 01-2 2v4a2 2 0 012 2h14a2 2 0 012-2v-4a2 2 0 01-2-2m-2-4h.01M17 16h.01"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Provider Status</p>
                @if(isset($stats['provider_balance']))
                    <h3 class="text-lg font-bold text-white">₦{{ number_format($stats['provider_balance'], 2) }}</h3>
                    <p class="text-[10px] text-emerald-400 uppercase font-bold tracking-wider">Connected</p>
                @else
                    <h3 class="text-lg font-bold text-white">Error</h3>
                    <p class="text-[10px] text-red-400 uppercase font-bold tracking-wider">Disconnected</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Success vs Failed -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-emerald-400 font-bold uppercase tracking-wider">Successful Txns</span>
                    <span class="text-white font-black">{{ $stats['successful_orders'] }}</span>
                </div>
                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-emerald-400" style="width: {{ $stats['total_orders'] > 0 ? ($stats['successful_orders'] / $stats['total_orders']) * 100 : 0 }}%"></div>
                </div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-red-400 font-bold uppercase tracking-wider">Failed Txns</span>
                    <span class="text-white font-black">{{ $stats['failed_orders'] }}</span>
                </div>
                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-red-400" style="width: {{ $stats['total_orders'] > 0 ? ($stats['failed_orders'] / $stats['total_orders']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat Cards -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Users</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($stats['total_users']) }}</h3>
            </div>
        </div>
    </div>

    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Revenue</p>
                <h3 class="text-2xl font-bold text-white">₦{{ number_format($stats['total_revenue'], 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Orders</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($stats['total_orders']) }}</h3>
            </div>
        </div>
    </div>

    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Data Plans</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['active_data_plans'] }} Active</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <!-- Recent Transactions -->
    <div class="glass rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-lg font-bold">Recent Transactions</h3>
            <a href="{{ route('admin.finance.transactions') }}" class="text-sm text-blue-400 hover:text-blue-300">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">User</th>
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">Source / Type</th>
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">Amount</th>
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recent_transactions as $transaction)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4">
                                <p class="text-sm font-bold text-white">{{ $transaction->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $transaction->user->email }}</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-medium text-white">{{ ucfirst($transaction->source) }}</p>
                                <p class="text-[10px] text-gray-500 uppercase font-black">{{ str_replace('_', ' ', $transaction->type) }}</p>
                            </td>
                            <td class="p-4 text-sm font-bold">₦{{ number_format($transaction->amount, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $transaction->status == 'completed' || $transaction->status == 'success' ? 'bg-emerald-500/20 text-emerald-400' : ($transaction->status == 'pending' ? 'bg-amber-500/20 text-amber-400' : 'bg-red-500/20 text-red-400') }}">
                                    {{ $transaction->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-gray-400">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-6">
        <h3 class="text-lg font-bold">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.vtu.plans') }}" class="glass p-6 rounded-2xl hover:bg-white/5 transition-all group">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h4 class="font-bold mb-1">Manage VTU</h4>
                <p class="text-xs text-gray-400">Configure plans and prices.</p>
            </a>
            

        </div>
    </div>
</div>
@endsection
