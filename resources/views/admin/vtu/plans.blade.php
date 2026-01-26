@extends('layouts.admin')

@section('title', 'VTU Data Plans')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h2 class="text-2xl font-bold">Manage Data Plans</h2>
    <form action="{{ route('admin.vtu.sync-epins') }}" method="POST">
        @csrf
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-blue-500/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Sync from EPINS
        </button>
    </form>
</div>

<div class="glass rounded-3xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                    <th class="p-6 font-bold">Network</th>
                    <th class="p-6 font-bold">Plan Name</th>
                    <th class="p-6 font-bold">API Price</th>
                    <th class="p-6 font-bold w-48">Selling Price</th>
                    <th class="p-6 font-bold">Status</th>
                    <th class="p-6 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($plans as $plan)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-lg bg-white/5 text-xs font-bold">{{ $plan->network }}</span>
                        </td>
                        <td class="p-6">
                            <p class="text-sm font-bold text-white">{{ $plan->name }}</p>
                            <p class="text-[10px] text-gray-500 font-mono">{{ $plan->code }}</p>
                        </td>
                        <td class="p-6 text-sm font-mono text-gray-400">₦{{ number_format($plan->provider_price, 2) }}</td>
                        <td class="p-6">
                            <form action="{{ route('admin.vtu.plans.update-price', $plan) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs text-sm">₦</span>
                                    <input type="number" step="0.01" name="selling_price" value="{{ $plan->selling_price }}" 
                                           class="w-32 bg-[#0f172a] border border-white/10 rounded-lg pl-6 pr-3 py-2 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <button type="submit" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 text-white transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                        </td>
                        <td class="p-6">
                            @if($plan->is_active)
                                <span class="flex items-center gap-1.5 text-emerald-400 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-red-400 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center">
                                <form action="{{ route('admin.vtu.plans.toggle-status', $plan) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-md border {{ $plan->is_active ? 'border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white' : 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-500 hover:text-white' }} transition-all">
                                        {{ $plan->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-white/10">
        {{ $plans->links() }}
    </div>
</div>
@endsection
