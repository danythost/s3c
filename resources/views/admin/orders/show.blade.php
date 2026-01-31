@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="glass p-2 rounded-xl text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold">Transaction Details</h2>
            <p class="text-sm text-gray-400 font-mono">{{ $order->reference }}</p>
        </div>
    </div>

    @if($order->status !== 'success')
        <form action="{{ route('admin.orders.retry', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to retry this transaction?')">
            @csrf
            <button type="submit" class="bg-blue-600 px-6 py-2 rounded-xl text-sm font-bold hover:bg-blue-500 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Retry Transaction
            </button>
        </form>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2">Overview</h3>
            <div class="grid grid-cols-2 gap-y-4 text-sm">
                <div>
                    <label class="block text-gray-400 text-xs uppercase mb-1">Status</label>
                    @php
                        $statusColors = [
                            'success' => 'text-emerald-400',
                            'failed' => 'text-red-400',
                            'pending' => 'text-amber-400',
                            'reversed' => 'text-purple-400',
                        ];
                    @endphp
                    <span class="font-bold {{ $statusColors[$order->status] ?? 'text-gray-400' }} uppercase">{{ $order->status }}</span>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs uppercase mb-1">Date</label>
                    <span class="text-white">{{ $order->created_at->format('M d, Y H:i:s') }}</span>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs uppercase mb-1">Amount</label>
                    <span class="text-xl font-bold font-mono text-white">₦{{ number_format($order->amount, 2) }}</span>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs uppercase mb-1">Service Type</label>
                    <span class="text-white uppercase">{{ $order->type }}</span>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs uppercase mb-1">Provider</label>
                    <span class="text-white font-mono text-xs p-1 bg-white/5 rounded">{{ $order->provider ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Service Details -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2">Service Details</h3>
            <div class="bg-[#0f172a] p-4 rounded-xl font-mono text-xs text-blue-300 overflow-x-auto">
                <pre>{{ json_encode($order->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
        
        <!-- Logs -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2">Request / Response Logs</h3>
            @if($order->api_response)
                <div class="space-y-4">
                    @foreach(is_array($order->api_response) ? $order->api_response : [$order->api_response] as $index => $log)
                        <div class="bg-black/30 p-4 rounded-xl">
                            <div class="text-xs text-gray-500 mb-2 font-mono">Log Entry #{{ $index + 1 }}</div>
                            <pre class="font-mono text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap">{{ json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No logs available.</p>
            @endif
        </div>
    </div>

    <!-- User Details Sidebar -->
    <div class="space-y-6">
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2">Customer</h3>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-lg">
                    {{ substr($order->user->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-white">{{ $order->user->name }}</h4>
                    <p class="text-xs text-gray-400">ID: {{ $order->user->id }}</p>
                </div>
            </div>
            <div class="space-y-3 text-sm">
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Email</label>
                    <a href="mailto:{{ $order->user->email }}" class="text-blue-400 hover:underline">{{ $order->user->email }}</a>
                </div>
                <div>
                    <label class="block text-gray-400 text-xs mb-1">Joined</label>
                    <span class="text-white">{{ $order->user->created_at->format('M Y') }}</span>
                </div>
                <div class="pt-4 mt-4 border-t border-white/10">
                    <a href="{{ route('admin.users.show', $order->user) }}" class="block w-full text-center px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-xs font-bold transition-all">
                        View User Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
