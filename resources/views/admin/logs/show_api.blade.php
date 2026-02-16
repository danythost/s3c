@extends('layouts.admin')

@section('title', 'API Log Details #' . $log->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.logs.api') }}" class="glass p-2 rounded-xl text-gray-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight">Log Details #{{ $log->id }}</h1>
                    <p class="text-gray-400 text-sm mt-1">Detailed view of the API transaction.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if($log->status_code >= 200 && $log->status_code < 300)
                <span class="px-4 py-2 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-black text-xs uppercase tracking-widest">
                    {{ $log->status_code }} SUCCESS
                </span>
            @else
                <span class="px-4 py-2 rounded-2xl bg-red-500/10 text-red-400 border border-red-500/20 font-black text-xs uppercase tracking-widest">
                    {{ $log->status_code }} ERROR
                </span>
            @endif
            <span class="px-4 py-2 rounded-2xl bg-white/5 text-gray-400 border border-white/10 font-black text-xs uppercase tracking-widest">
                {{ $log->duration_ms }}ms
            </span>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass p-8 rounded-[2rem] border border-white/5">
            <div class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-4">Transaction Info</div>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 text-xs">Provider:</span>
                    <span class="text-white text-xs font-bold uppercase">{{ $log->provider }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-xs">Method:</span>
                    <span class="text-yellow-400 text-xs font-bold">{{ $log->method }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 text-xs">Time:</span>
                    <span class="text-white text-xs font-mono">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
            </div>
        </div>

        <div class="glass p-8 rounded-[2rem] border border-white/5 md:col-span-2">
            <div class="text-[10px] font-black uppercase text-gray-500 tracking-widest mb-4">Request URL</div>
            <div class="bg-black/20 p-4 rounded-xl font-mono text-sm text-blue-300 break-all border border-white/5">
                {{ $log->url }}
            </div>
        </div>
    </div>

    <!-- Payload Sections -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Request Payload -->
        <div class="glass rounded-[2rem] overflow-hidden flex flex-col h-[600px]">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Request Payload</h3>
                <button onclick="copyToClipboard('request-payload')" class="text-[10px] font-bold text-blue-400 hover:text-blue-300 uppercase tracking-widest transition-colors">
                    Copy JSON
                </button>
            </div>
            <div class="p-6 flex-1 overflow-hidden">
                <pre id="request-payload" class="h-full bg-black/30 p-6 rounded-2xl text-xs text-blue-300 overflow-auto font-mono custom-scrollbar border border-white/5">{{ json_encode($log->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>

        <!-- Response Payload -->
        <div class="glass rounded-[2rem] overflow-hidden flex flex-col h-[600px]">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Response Payload</h3>
                <button onclick="copyToClipboard('response-payload')" class="text-[10px] font-bold text-emerald-400 hover:text-emerald-300 uppercase tracking-widest transition-colors">
                    Copy JSON
                </button>
            </div>
            <div class="p-6 flex-1 overflow-hidden">
                <pre id="response-payload" class="h-full bg-black/30 p-6 rounded-2xl text-xs text-emerald-300 overflow-auto font-mono custom-scrollbar border border-white/5">{{ json_encode($log->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(elementId) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                title: 'Copied!',
                text: 'JSON copied to clipboard',
                icon: 'success',
                background: '#1a1a2e',
                color: '#fff',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'rounded-xl border border-white/10 glass'
                }
            });
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
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255,255,255,0.2);
    }
</style>
@endsection
