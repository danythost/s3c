@extends('layouts.app')

@section('title', 'Wallet Balance')
@section('header', 'My Wallet')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Balance Card -->
    <div class="glass p-8 rounded-2xl shadow-2xl relative overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
        
        <div class="relative">
            <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Available Balance</span>
            <div class="mt-2 flex items-baseline">
                <span class="text-4xl font-extrabold text-white">₦ {{ number_format($wallet->balance ?? 0, 2) }}</span>
                <span class="ml-2 text-emerald-400 text-sm font-medium bg-emerald-400/10 px-2 py-0.5 rounded-full">{{ $wallet->status ?? 'Active' }}</span>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4">
            <button class="glass border-white/5 hover:bg-white/10 p-4 rounded-xl text-center transition-all">
                <span class="block text-xl mb-1">💳</span>
                <span class="text-xs font-semibold text-gray-400">Top Up</span>
            </button>
            <button class="glass border-white/5 hover:bg-white/10 p-4 rounded-xl text-center transition-all">
                <span class="block text-xl mb-1">📤</span>
                <span class="text-xs font-semibold text-gray-400">Transfer</span>
            </button>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="glass p-8 rounded-2xl shadow-2xl">
        <h3 class="text-xl font-bold mb-6">Recent Transactions</h3>
        <div class="space-y-4">
            @forelse($transactions ?? [] as $transaction)
                <div class="flex items-center justify-between p-4 glass rounded-xl border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->type == 'credit' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $transaction->type == 'credit' ? '↓' : '↑' }}
                        </div>
                        <div>
                            <p class="font-semibold text-sm">{{ $transaction->description }}</p>
                            <span class="text-xs text-gray-500">{{ $transaction->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold {{ $transaction->type == 'credit' ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $transaction->type == 'credit' ? '+' : '-' }} ₦{{ number_format($transaction->amount, 2) }}
                        </p>
                        <span class="text-[10px] text-gray-400 uppercase">{{ $transaction->status }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-10">
                    <p class="text-gray-500 italic">No transactions found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
