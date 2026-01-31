@extends('layouts.admin')

@section('title', 'Funding History')

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
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-blue-400 border-b-2 border-blue-400">
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

<!-- Search & Filters -->
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('admin.finance.transactions') }}" class="px-3 py-1 text-xs rounded-lg {{ !request('type') ? 'bg-white/10' : 'bg-transparent text-gray-400' }}">All</a>
        <a href="{{ route('admin.finance.transactions', ['type' => 'credit']) }}" class="px-3 py-1 text-xs rounded-lg {{ request('type') == 'credit' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-transparent text-gray-400' }}">Credits</a>
        <a href="{{ route('admin.finance.transactions', ['type' => 'debit']) }}" class="px-3 py-1 text-xs rounded-lg {{ request('type') == 'debit' ? 'bg-red-500/20 text-red-400' : 'bg-transparent text-gray-400' }}">Debits</a>
        <a href="{{ route('admin.finance.transactions', ['type' => 'commission']) }}" class="px-3 py-1 text-xs rounded-lg {{ request('type') == 'commission' ? 'bg-amber-500/20 text-amber-400' : 'bg-transparent text-gray-400' }}">Commissions</a>
    </div>
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="type" value="{{ request('type') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="User or Reference..." 
               class="bg-[#0f172a] glass px-4 py-2 rounded-xl text-xs outline-none border border-transparent focus:border-blue-500 transition-all w-64">
        <button type="submit" class="glass px-4 py-2 rounded-xl text-xs font-bold hover:bg-white/10">Search</button>
    </form>
</div>

<!-- Table -->
<div class="glass rounded-3xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                    <th class="p-6 font-bold">Reference</th>
                    <th class="p-6 font-bold">User</th>
                    <th class="p-6 font-bold">Type</th>
                    <th class="p-6 font-bold">Amount</th>
                    <th class="p-6 font-bold">Date</th>
                    <th class="p-6 font-bold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($transactions as $txn)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-6 font-mono text-xs">{{ $txn->reference }}</td>
                        <td class="p-6">
                            <h4 class="text-sm font-bold text-white">{{ $txn->user->name ?? 'Unknown' }}</h4>
                            <p class="text-[10px] text-gray-500">{{ $txn->user->email ?? 'N/A' }}</p>
                        </td>
                        <td class="p-6">
                            <span class="uppercase text-xs font-bold {{ str_contains($txn->type, 'credit') ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ str_replace('_', ' ', $txn->type) }}
                            </span>
                        </td>
                        <td class="p-6 font-mono font-bold text-sm">₦{{ number_format($txn->amount, 2) }}</td>
                        <td class="p-6 text-xs text-gray-400">{{ $txn->created_at->format('M d, H:i') }}</td>
                        <td class="p-6">
                            <span class="px-2 py-1 rounded-full text-[10px] uppercase font-bold {{ $txn->status == 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $txn->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400">No transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-white/10">
        {{ $transactions->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection
