@extends('layouts.admin')

@section('title', 'API & Webhook Logs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">API Logs</h1>
            <p class="text-gray-400 text-sm mt-1">Monitor outgoing API requests and incoming Webhooks.</p>
        </div>
        
        <!-- Search -->
        <form method="GET" class="flex items-center gap-2">
            <div class="relative">
                <input type="text" name="ref" value="{{ request('ref') }}" 
                       placeholder="Search Ref/Data..." 
                       class="glass pl-10 pr-4 py-2 rounded-xl text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 w-64">
                <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <select name="provider" onchange="this.form.submit()" class="glass px-4 py-2 rounded-xl text-sm text-gray-300 focus:outline-none cursor-pointer">
                <option value="">All Providers</option>
                <option value="epins" {{ request('provider') == 'epins' ? 'selected' : '' }}>Epins (Outgoing)</option>
                <option value="epins-webhook" {{ request('provider') == 'epins-webhook' ? 'selected' : '' }}>Epins (Webhook)</option>
                <option value="flutterwave" {{ request('provider') == 'flutterwave' ? 'selected' : '' }}>Flutterwave</option>
            </select>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="glass rounded-[2rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/5 text-left">
                        <th class="p-6 text-xs font-black text-gray-500 uppercase tracking-widest">Time</th>
                        <th class="p-6 text-xs font-black text-gray-500 uppercase tracking-widest">Provider</th>
                        <th class="p-6 text-xs font-black text-gray-500 uppercase tracking-widest">Method / URL</th>
                        <th class="p-6 text-xs font-black text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="p-6 text-xs font-black text-gray-500 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($logs as $log)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="p-6 text-sm text-gray-400 font-mono">
                                {{ $log->created_at->format('M d, H:i:s') }}
                                <div class="text-[10px] text-gray-600">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="p-6">
                                @if($log->provider === 'epins-webhook')
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400 border border-purple-500/20">WEBHOOK</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase">{{ $log->provider }}</span>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold {{ $log->method === 'POST' ? 'text-yellow-400' : 'text-green-400' }}">{{ $log->method }}</span>
                                    <div class="text-sm text-gray-300 truncate max-w-xs" title="{{ $log->url }}">
                                        {{ Str::limit($log->url, 40) }}
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                @if($log->status_code >= 200 && $log->status_code < 300)
                                    <span class="text-emerald-400 font-bold text-xs">{{ $log->status_code }} OK</span>
                                @else
                                    <span class="text-red-400 font-bold text-xs">{{ $log->status_code }} ERR</span>
                                @endif
                                <div class="text-[10px] text-gray-500">{{ $log->duration_ms }}ms</div>
                            </td>
                            <td class="p-6 text-right">
                                <button onclick="viewLog({{ $log->id }})" class="text-blue-400 hover:text-blue-300 text-xs font-bold uppercase tracking-wider transition-colors">
                                    View Details
                                </button>
                                
                                <!-- Hidden Data for Modal -->
                                <textarea id="req-{{ $log->id }}" class="hidden">{{ json_encode($log->request_payload, JSON_PRETTY_PRINT) }}</textarea>
                                <textarea id="res-{{ $log->id }}" class="hidden">{{ json_encode($log->response_payload, JSON_PRETTY_PRINT) }}</textarea>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-500">
                                No logs found using current filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="p-6 border-t border-white/5">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function viewLog(id) {
        const req = document.getElementById(`req-${id}`).value;
        const res = document.getElementById(`res-${id}`).value;

        Swal.fire({
            title: 'Log Details #' + id,
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <div class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-1">Request Payload</div>
                        <pre class="bg-black/30 p-4 rounded-xl text-xs text-blue-300 overflow-x-auto font-mono custom-scrollbar max-h-48">${req}</pre>
                    </div>
                    <div>
                        <div class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-1">Response Payload</div>
                        <pre class="bg-black/30 p-4 rounded-xl text-xs text-emerald-300 overflow-x-auto font-mono custom-scrollbar max-h-48">${res}</pre>
                    </div>
                </div>
            `,
            background: '#1a1a2e',
            color: '#fff',
            confirmButtonColor: '#3b82f6',
            width: '800px',
            customClass: {
                popup: 'rounded-[2rem] border border-white/10 glass shadow-2xl',
                confirmButton: 'rounded-xl px-8 py-4 font-black uppercase tracking-widest text-xs'
            }
        });
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
    }
</style>
@endsection
