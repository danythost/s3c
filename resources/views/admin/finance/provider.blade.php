@extends('layouts.admin')

@section('title', 'Provider Wallets')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Finance & Wallet</h2>
    
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1">
        <a href="{{ route('admin.finance.index') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
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
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-blue-400 border-b-2 border-blue-400">
            Provider Wallets
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- EPINS -->
    <div class="glass p-8 rounded-3xl relative overflow-hidden group">
        <div class="absolute -right-12 -top-12 w-32 h-32 bg-purple-500/20 rounded-full blur-3xl group-hover:bg-purple-500/30 transition-all"></div>
        <div class="relative">
            <h3 class="text-xl font-bold text-white mb-2">EPINS Wallet</h3>
            <p class="text-sm text-gray-400 mb-6">Primary VTU Provider</p>
            
            <div class="flex items-end gap-2 mb-4">
                <span class="text-4xl font-bold text-white">
                    @if(isset($epinsBalance))
                        ₦{{ number_format($epinsBalance, 2) }}
                    @else
                        <span class="text-gray-500 text-lg">Error/Unavailable</span>
                    @endif
                </span>
                <span class="text-sm text-gray-500 mb-1">Balance</span>
            </div>

            @if(isset($epinsBalance) && $epinsBalance < 5000)
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-500/10 text-red-400 text-xs font-bold ring-1 ring-red-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Low Balance Alert
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-400 text-xs font-bold ring-1 ring-emerald-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                    Healthy Balance
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
