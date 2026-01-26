@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
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
    <!-- Recent Orders -->
    <div class="glass rounded-3xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <h3 class="text-lg font-bold">Recent Orders</h3>
            <a href="#" class="text-sm text-blue-400 hover:text-blue-300">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">User</th>
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">Type</th>
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">Amount</th>
                        <th class="p-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recent_orders as $order)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4">
                                <p class="text-sm font-bold text-white">{{ $order->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $order->user->email }}</p>
                            </td>
                            <td class="p-4 text-sm">{{ ucfirst($order->type) }}</td>
                            <td class="p-4 text-sm font-bold">₦{{ number_format($order->amount, 2) }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $order->status == 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-gray-400">No orders found.</td>
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
            
            <form action="{{ route('admin.vtu.sync-epins') }}" method="POST" class="contents">
                @csrf
                <button type="submit" class="glass p-6 rounded-2xl hover:bg-white/5 transition-all group text-left w-full">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4 group-hover:rotate-180 transition-transform duration-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h4 class="font-bold mb-1">Sync EPINS</h4>
                    <p class="text-xs text-gray-400">Fetch latest data plans.</p>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
