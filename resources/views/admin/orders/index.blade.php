@extends('layouts.admin')

@section('title', 'Transactions & Operations')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Transactions & Operations</h2>
    
    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1">
        <a href="{{ route('admin.orders.index') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ !request('status') ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400' }}">
            All Transactions
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'success']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'success' ? 'text-emerald-400 border-b-2 border-emerald-400' : 'text-gray-400' }}">
            Successful
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'failed']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'failed' ? 'text-red-400 border-b-2 border-red-400' : 'text-gray-400' }}">
            Failed
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'pending' ? 'text-amber-400 border-b-2 border-amber-400' : 'text-gray-400' }}">
            Pending
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'reversed']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'reversed' ? 'text-purple-400 border-b-2 border-purple-400' : 'text-gray-400' }}">
            Reversed
        </a>
    </div>
</div>

<!-- Search -->
<div class="mb-6 flex justify-end">
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Ref or User Email..." 
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
                    <th class="p-6 font-bold">Service</th>
                    <th class="p-6 font-bold">Amount</th>
                    <th class="p-6 font-bold">Provider</th>
                    <th class="p-6 font-bold">Date</th>
                    <th class="p-6 font-bold">Status</th>
                    <th class="p-6 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($transactions as $transaction)
    <tr class="hover:bg-white/5 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $transaction->id) }}'">
        <td class="p-6">
            <span class="font-mono text-xs text-blue-400">{{ $transaction->reference }}</span>
        </td>
        <td class="p-6">
            <div class="text-sm font-bold text-white">{{ $transaction->user->name ?? 'Unknown' }}</div>
            <div class="text-xs text-gray-500">{{ $transaction->user->email ?? '-' }}</div>
        </td>
        <td class="p-6">
            <span class="px-2 py-1 rounded bg-white/5 text-xs">{{ strtoupper($transaction->source ?? $transaction->type) }}</span>
            @if(isset($transaction->meta['network']))
                <span class="text-xs text-gray-400 ml-1">{{ $transaction->meta['network'] }}</span>
            @endif
        </td>
        <td class="p-6 font-mono font-bold text-sm">₦{{ number_format($transaction->amount, 2) }}</td>
        <td class="p-6 text-xs text-gray-400 font-mono">{{ $transaction->meta['provider'] ?? '-' }}</td>
        <td class="p-6 text-xs text-gray-400">{{ $transaction->created_at->format('M d, H:i') }}</td>
        <td class="p-6">
            @php
                $statusColors = [
                    'success' => 'bg-emerald-500/20 text-emerald-400',
                    'failed' => 'bg-red-500/20 text-red-400',
                    'pending' => 'bg-amber-500/20 text-amber-400',
                    'reversed' => 'bg-purple-500/20 text-purple-400',
                ];
                $color = $statusColors[$transaction->status] ?? 'bg-gray-500/20 text-gray-400';
            @endphp
            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $color }}">
                {{ $transaction->status }}
            </span>
        </td>
        <td class="p-6 text-center">
            <a href="{{ route('admin.orders.show', $transaction->id) }}" class="text-xs font-bold text-blue-400 hover:text-blue-300">View</a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="p-10 text-center text-gray-400">No transactions found.</td>
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
