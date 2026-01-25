@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Welcome back, ' . auth()->user()->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Wallet Overview -->
    <div class="glass p-8 rounded-2xl shadow-2xl border-l-4 border-emerald-500">
        <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Wallet Balance</h3>
        <p class="mt-2 text-3xl font-bold">₦ {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</p>
        <div class="mt-6">
            <a href="{{ route('wallet.index') }}" class="text-sm text-emerald-400 hover:text-emerald-300 font-medium">View History →</a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="glass p-8 rounded-2xl shadow-2xl border-l-4 border-blue-500">
        <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Orders</h3>
        <p class="mt-2 text-3xl font-bold">{{ auth()->user()->orders()->count() }}</p>
        <div class="mt-6">
            <span class="text-xs text-blue-400 bg-blue-400/10 px-2 py-1 rounded-full">All time</span>
        </div>
    </div>
</div>

<div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Quick Actions -->
    <div class="glass p-8 rounded-2xl shadow-2xl">
        <h3 class="text-xl font-bold mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('vtu.data.index') }}" class="glass p-6 rounded-xl hover:bg-white/10 transition-all text-center group">
                <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">📱</div>
                <span class="block font-bold">Buy Data</span>
                <span class="text-xs text-gray-500">Fast & Secure</span>
            </a>
            <a href="#" class="glass p-6 rounded-xl hover:bg-white/10 transition-all text-center group">
                <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">📞</div>
                <span class="block font-bold">Buy Airtime</span>
                <span class="text-xs text-gray-500">All Networks</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="glass p-8 rounded-2xl shadow-2xl">
        <h3 class="text-xl font-bold mb-6">Recent Activity</h3>
        <div class="space-y-4">
            @forelse(auth()->user()->orders()->latest()->take(5)->get() as $order)
                <div class="flex items-center justify-between p-4 glass rounded-xl border-white/5">
                    <div>
                        <p class="font-semibold text-sm">{{ ucfirst($order->type) }} Purchase</p>
                        <span class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">₦{{ number_format($order->amount, 2) }}</p>
                        <span class="text-[10px] uppercase {{ $order->status == 'success' ? 'text-emerald-400' : 'text-red-400' }}">{{ $order->status }}</span>
                    </div>
                </div>
            @empty
                <p class="text-center py-10 text-gray-500 italic">No recent activity</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
