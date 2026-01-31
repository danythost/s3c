@extends('layouts.admin')

@section('title', 'Finance Overview')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Finance & Wallet</h2>
    
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1">
        <a href="{{ route('admin.finance.index') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-blue-400 border-b-2 border-blue-400">
            Overview
        </a>
        <a href="{{ route('admin.finance.transactions') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
            Funding History
        </a>
        <a href="{{ route('admin.finance.fund') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
            Manual Funding
        </a>
        <a href="{{ route('admin.finance.provider') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
            Provider Wallets
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- User Balances -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total User Funds</p>
                <h3 class="text-2xl font-bold text-white">₦{{ number_format($stats['total_user_balance'], 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Funded Volume -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Total Credited</p>
                <h3 class="text-2xl font-bold text-white">₦{{ number_format($stats['total_funded'], 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Transactions -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Transactions</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($stats['total_transactions']) }}</h3>
            </div>
        </div>
    </div>

    <!-- Commissions -->
    <div class="glass p-6 rounded-2xl">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <div>
                <p class="text-sm text-gray-400">Commissions</p>
                <h3 class="text-2xl font-bold text-white">₦{{ number_format($stats['total_commissions'], 2) }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection
