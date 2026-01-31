@extends('layouts.admin')

@section('title', 'Communication Logs')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Content & Communication</h2>
    
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1">
        <a href="{{ route('admin.content.index') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
            Announcements
        </a>
        <a href="{{ route('admin.content.logs') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-blue-400 border-b-2 border-blue-400">
            Communication Logs
        </a>
    </div>
</div>

<!-- Search & Filters -->
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('admin.content.logs') }}" class="px-3 py-1 text-xs rounded-lg {{ !request('channel') ? 'bg-white/10' : 'bg-transparent text-gray-400' }}">All</a>
        <a href="{{ route('admin.content.logs', ['channel' => 'sms']) }}" class="px-3 py-1 text-xs rounded-lg {{ request('channel') == 'sms' ? 'bg-blue-500/20 text-blue-400' : 'bg-transparent text-gray-400' }}">SMS</a>
        <a href="{{ route('admin.content.logs', ['channel' => 'email']) }}" class="px-3 py-1 text-xs rounded-lg {{ request('channel') == 'email' ? 'bg-purple-500/20 text-purple-400' : 'bg-transparent text-gray-400' }}">Email</a>
    </div>
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="channel" value="{{ request('channel') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Recipient or message..." 
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
                    <th class="p-6 font-bold">Details</th>
                    <th class="p-6 font-bold">User</th>
                    <th class="p-6 font-bold">Message</th>
                    <th class="p-6 font-bold">Status</th>
                    <th class="p-6 font-bold">Date</th>
                    <th class="p-6 font-bold text-center">API</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($logs as $log)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-6">
                            <div class="flex items-center gap-2">
                                @if($log->channel == 'sms')
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-xs font-bold text-white">{{ $log->recipient }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase">{{ $log->channel }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            @if($log->user)
                                <a href="{{ route('admin.users.show', $log->user) }}" class="text-xs text-blue-400 hover:underline">{{ $log->user->name }}</a>
                            @else
                                <span class="text-xs text-gray-500">Guest</span>
                            @endif
                        </td>
                        <td class="p-6 text-xs text-gray-400 max-w-sm">
                            <p class="truncate">{{ $log->message }}</p>
                        </td>
                        <td class="p-6">
                            <span class="px-2 py-1 rounded text-[10px] uppercase font-bold 
                                {{ $log->status == 'sent' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td class="p-6 text-[10px] text-gray-500">{{ $log->created_at->format('M d, H:i') }}</td>
                        <td class="p-6 text-center">
                            @if($log->provider_response)
                                <button onclick="viewLogResponse({{ json_encode($log->provider_response) }})" class="text-gray-500 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </button>
                            @else
                                <span class="text-gray-700">--</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400">No communication logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-white/10">
        {{ $logs->links('vendor.pagination.admin') }}
    </div>
</div>

<dialog id="responseModal" class="glass bg-slate-900 text-white p-0 rounded-3xl w-full max-w-lg backdrop:bg-black/50">
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">API Response Logs</h3>
            <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-white">✕</button>
        </div>
        <pre id="responseContent" class="bg-black/40 p-4 rounded-xl text-[10px] font-mono overflow-auto max-h-[400px] text-emerald-400"></pre>
    </div>
</dialog>

<script>
    function viewLogResponse(resp) {
        document.getElementById('responseContent').innerText = JSON.stringify(resp, null, 2);
        document.getElementById('responseModal').showModal();
    }
</script>
@endsection
