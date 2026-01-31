@extends('layouts.admin')

@section('title', 'Manual Funding')

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
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-blue-400 border-b-2 border-blue-400">
            Manual Funding
        </a>
        <a href="{{ route('admin.finance.provider') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
            Provider Wallets
        </a>
    </div>
</div>

<div class="flex justify-center">
    <div class="glass w-full max-w-lg p-8 rounded-3xl">
        <h3 class="text-xl font-bold mb-6">Fund User Wallet</h3>
        
        <form action="{{ route('admin.finance.fund.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">User Email</label>
                <input type="email" name="email" required placeholder="user@example.com" 
                       class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none transition-all">
                <p class="text-xs text-gray-500 mt-1">Make sure the email is correct.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Action Type</label>
                    <select name="type" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none">
                        <option value="credit">Credit (Deposit)</option>
                        <option value="debit">Debit (Withdrawal)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">₦</span>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00" 
                               class="w-full bg-[#0f172a] border border-white/10 rounded-xl pl-8 pr-4 py-3 text-sm focus:border-blue-500 outline-none">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Description / Reason</label>
                <textarea name="description" required rows="3" placeholder="Reason for this funding..." 
                          class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none"></textarea>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold transition-all shadow-lg shadow-blue-500/20">
                Process Transaction
            </button>
        </form>
    </div>
</div>
@endsection
