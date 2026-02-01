@extends('layouts.app')

@section('title', 'Wallet Balance')
@section('header', 'My Wallet')

@section('content')
<div class="space-y-10">
    <!-- Wallet Hero -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Balance Card -->
        <div class="glass p-10 rounded-[2.5rem] border-white/10 bg-gradient-to-br from-emerald-500/10 to-blue-500/10 relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-500"></div>
            
            <div class="relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Digital Wallet</span>
                </div>
                
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-widest block">Available Funds</span>
                    <form action="{{ route('wallet.refresh') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[9px] bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full uppercase font-black tracking-widest border border-emerald-500/20 hover:bg-emerald-500 hover:text-white transition-all">
                            Refresh Balance
                        </button>
                    </form>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-black text-white tracking-tighter">₦ {{ number_format($wallet->balance ?? 0, 2) }}</span>
                    <span class="text-emerald-400 font-bold text-sm bg-emerald-400/10 px-3 py-1 rounded-full border border-emerald-400/20">Active</span>
                </div>

                <div class="mt-12 flex gap-4">
                    <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-3 rounded-2xl font-bold text-xs shadow-xl shadow-emerald-500/20 transition-all transform hover:scale-105">
                        Transfer Funds
                    </button>
                </div>
            </div>
        </div>

        <!-- Funding Method (Virtual Account) -->
        <div class="glass p-10 rounded-[2.5rem] border-white/5 relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl group-hover:bg-blue-500/10 transition-all duration-500"></div>
            
            <div class="relative h-full flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-bold text-white tracking-tight">Instant Funding</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-[9px] bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full uppercase font-black tracking-widest border border-blue-500/20">Bank Transfer</span>
                    </div>
                </div>

                @if(isset($virtualAccount))
                    <div class="space-y-6 flex-1">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-black mb-1 tracking-tighter">Bank Entity</p>
                                <p class="text-white font-bold text-sm">{{ $virtualAccount->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-black mb-1 tracking-tighter">Account Holder</p>
                                <p class="text-white font-bold text-sm truncate">S3C - {{ auth()->user()->name }}</p>
                            </div>
                        </div>
                        
                        <div class="glass bg-white/5 p-4 rounded-3xl border-white/5 group/copy relative">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Account Number</p>
                            <div class="flex items-center justify-between">
                                <p class="text-3xl font-mono font-black text-white tracking-[0.1em]">{{ $virtualAccount->account_number }}</p>
                                <button onclick="navigator.clipboard.writeText('{{ $virtualAccount->account_number }}'); alert('Copied!')" 
                                        class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-4 italic font-medium">Any transfer to this account will credit your wallet automatically within 2-5 minutes.</p>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center py-6">
                        <div class="w-12 h-12 border-4 border-blue-500/20 border-t-blue-500 rounded-full animate-spin mb-4"></div>
                        <p class="text-gray-400 text-sm font-medium">Generating your dedicated bank details...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-white tracking-tight">Unified Transactions</h3>
            <div class="flex gap-2">
                <button class="glass px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">Filter</button>
                <button class="glass px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-white transition-colors">Export</button>
            </div>
        </div>

        <div class="glass rounded-[2rem] border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-white/5 bg-white/5">
                            <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-500 tracking-[0.2em]">Transaction / Date</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-500 tracking-[0.2em]">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-500 tracking-[0.2em]">Amount</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] text-right">Reference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($transactions ?? [] as $transaction)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl {{ $transaction->type == 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400' }} flex items-center justify-center font-bold">
                                            {{ $transaction->type == 'credit' ? '↓' : '↑' }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white tracking-tight">
                                                {{ $transaction->type == 'credit' ? 'Wallet Funding' : 'Service Payment' }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $transaction->created_at->format('M d, Y • h:i A') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest {{ $transaction->status == 'success' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-sm font-black {{ $transaction->type == 'credit' ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $transaction->type == 'credit' ? '+' : '-' }} ₦{{ number_format($transaction->amount, 2) }}
                                    </p>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-xs font-mono text-gray-500 group-hover:text-white transition-colors">{{ $transaction->reference }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <p class="text-gray-500 italic text-sm">You haven't made any transactions yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
