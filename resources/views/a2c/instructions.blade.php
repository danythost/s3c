@extends('layouts.app')

@section('title', 'Transfer Instructions')

@section('header', 'SEND AIRTIME TO RECIPIENT')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="glass rounded-3xl p-8 border-white/10 relative overflow-hidden">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-white uppercase tracking-tight">Step 2: Transfer Airtime</h2>
            <div class="px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-black tracking-widest uppercase">
                Awaiting Transfer
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-6 rounded-2xl bg-red-500/10 border border-red-500/20">
                <div class="flex gap-4">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <h4 class="text-red-400 font-bold uppercase tracking-wider text-xs mb-1">Critical Notice</h4>
                        <p class="text-gray-400 text-xs leading-5">Send exactly <strong>₦{{ number_format($request->amount) }}</strong> {{ $request->network }} airtime to the number below. Do NOT send twice or a different amount.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-6 border border-white/5 space-y-4">
                <div class="flex justify-between items-center border-b border-white/5 pb-4">
                    <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">Recipient Number</span>
                    <div class="flex items-center gap-3">
                        <span class="text-white font-extrabold text-lg select-all" id="recipient_number">{{ $request->sitephone }}</span>
                        <button onclick="copyToClipboard('recipient_number')" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-blue-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center border-b border-white/5 pb-4">
                    <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">Reference</span>
                    <span class="text-white font-bold opacity-70">{{ $request->reference }}</span>
                </div>

                <div>
                    <label class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-3 block">USSD Transfer Code</label>
                    <div class="bg-[#0f172a] rounded-xl p-4 border border-white/5 flex items-center justify-between">
                        <code class="text-emerald-400 font-mono text-sm break-all" id="ussd_code">
                            {{ str_replace(['AMOUNT', 'RECIPIENT'], [$request->amount, $request->sitephone], config('airtime2cash.ussd.' . $request->network)) }}
                        </code>
                        <button onclick="copyToClipboard('ussd_code')" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-emerald-400 transition-colors flex-shrink-0 ml-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                        </button>
                    </div>
                    <p class="text-[9px] text-gray-600 mt-2 font-bold uppercase italic">*Replace 'PIN' with your transfer PIN.</p>
                </div>
            </div>

            <form action="{{ route('a2c.confirm', $request->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-5 rounded-2xl transition-all shadow-lg shadow-blue-500/25 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                    I Have Sent the Airtime
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </button>
            </form>
            
            <p class="text-center text-xs text-gray-500 uppercase font-black tracking-widest cursor-pointer hover:text-red-400 transition-colors">Cancel Request</p>
        </div>
    </div>
</div>

<script>
function copyToClipboard(id) {
    const text = document.getElementById(id).innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Copied to clipboard!');
    });
}
</script>
@endsection
