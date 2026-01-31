@extends('layouts.admin')

@section('title', 'Security & Monitoring')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Security & Audit</h2>
    
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1" x-data="{ tab: 'login' }">
        <button @click="tab = 'login'" 
           :class="tab === 'login' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            Login History
        </button>
        <button @click="tab = 'activity'" 
           :class="tab === 'activity' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            Admin Activity
        </button>
        <button @click="tab = 'api'" 
           :class="tab === 'api' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            API Logs
        </button>

        <div class="mt-8 w-full">
            <!-- Login History Tab -->
            <div x-show="tab === 'login'">
                <div class="glass rounded-3xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                                    <th class="p-6 font-bold">User/Email</th>
                                    <th class="p-6 font-bold">Role</th>
                                    <th class="p-6 font-bold">IP & Device</th>
                                    <th class="p-6 font-bold">Status</th>
                                    <th class="p-6 font-bold">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($loginHistory as $item)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="p-6">
                                            <div class="text-xs font-bold text-white">{{ $item->email }}</div>
                                            @if($item->user)
                                                <div class="text-[10px] text-blue-400 italic">Account Linked</div>
                                            @endif
                                        </td>
                                        <td class="p-6">
                                            <span class="px-2 py-1 rounded text-[10px] uppercase font-bold {{ $item->role == 'admin' ? 'bg-purple-500/20 text-purple-400' : 'bg-gray-500/20 text-gray-400' }}">
                                                {{ $item->role ?? 'Guest' }}
                                            </span>
                                        </td>
                                        <td class="p-6 text-xs text-gray-400">
                                            <p>{{ $item->ip_address }}</p>
                                            <p class="text-[10px] truncate max-w-[200px]">{{ $item->user_agent }}</p>
                                        </td>
                                        <td class="p-6">
                                            <span class="px-2 py-1 rounded text-[10px] uppercase font-bold {{ $item->status == 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="p-6 text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-10 text-center text-gray-500">No login records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Activity Tab -->
            <div x-show="tab === 'activity'">
                <div class="glass rounded-3xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                                    <th class="p-6 font-bold">Admin</th>
                                    <th class="p-6 font-bold">Action</th>
                                    <th class="p-6 font-bold">Description</th>
                                    <th class="p-6 font-bold">IP</th>
                                    <th class="p-6 font-bold">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($auditLogs as $log)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="p-6 font-bold text-sm text-white">{{ $log->user->name ?? 'System' }}</td>
                                        <td class="p-6 font-mono text-xs text-blue-400 uppercase">{{ $log->action }}</td>
                                        <td class="p-6 text-xs text-gray-400">{{ $log->description }}</td>
                                        <td class="p-6 text-xs text-gray-500">{{ $log->ip_address }}</td>
                                        <td class="p-6 text-xs text-gray-500">{{ $log->created_at->format('M d, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- API Logs Tab -->
            <div x-show="tab === 'api'">
                <div class="glass rounded-3xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                                    <th class="p-6 font-bold">Provider</th>
                                    <th class="p-6 font-bold">Endpoint</th>
                                    <th class="p-6 font-bold">Status</th>
                                    <th class="p-6 font-bold text-center">Duration</th>
                                    <th class="p-6 font-bold">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($apiLogs as $log)
                                    <tr class="hover:bg-white/5 transition-colors cursor-pointer" onclick="viewApiLog({{ json_encode($log) }})">
                                        <td class="p-6 text-xs font-bold uppercase text-white">{{ $log->provider }}</td>
                                        <td class="p-6 text-[10px] text-gray-400 font-mono">{{ $log->method }} {{ Str::afterLast($log->url, '/') }}</td>
                                        <td class="p-6">
                                            <span class="px-2 py-1 rounded text-[10px] font-bold {{ $log->status_code >= 400 ? 'bg-red-500/20 text-red-400' : 'bg-emerald-500/20 text-emerald-400' }}">
                                                {{ $log->status_code }}
                                            </span>
                                        </td>
                                        <td class="p-6 text-center text-[10px] text-gray-500">{{ $log->duration_ms }}ms</td>
                                        <td class="p-6 text-[10px] text-gray-500">{{ $log->created_at->format('H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-10 text-center text-gray-500">No API logs yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<dialog id="apiLogModal" class="glass bg-slate-900 text-white p-0 rounded-3xl w-full max-w-2xl backdrop:bg-black/50">
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">API Transaction Details</h3>
            <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-white">✕</button>
        </div>
        <div class="space-y-6">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Request Endpoint</label>
                <div id="log_url" class="p-3 bg-white/5 rounded-xl text-xs font-mono truncate"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Request Payload</label>
                    <pre id="log_request" class="p-4 bg-black/40 rounded-xl text-[10px] font-mono overflow-auto max-h-64 text-blue-300"></pre>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">Response JSON</label>
                    <pre id="log_response" class="p-4 bg-black/40 rounded-xl text-[10px] font-mono overflow-auto max-h-64 text-emerald-300"></pre>
                </div>
            </div>
        </div>
    </div>
</dialog>

<script>
    function viewApiLog(log) {
        document.getElementById('log_url').innerText = log.method + ' ' + log.url;
        document.getElementById('log_request').innerText = JSON.stringify(log.request_payload, null, 2);
        document.getElementById('log_response').innerText = JSON.stringify(log.response_payload, null, 2);
        document.getElementById('apiLogModal').showModal();
    }
</script>
@endsection
