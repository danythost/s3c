@extends('layouts.app')

@section('title', 'Request Status')

@section('header', 'VERIFYING YOUR TRANSFER')

@section('content')
<div class="max-w-xl mx-auto" x-data="{ timer: 5, refresh() { window.location.reload() } }" x-init="setInterval(() => { if(timer > 0) timer--; else refresh() }, 1000)">
    <div class="glass rounded-3xl p-8 border-white/10 relative overflow-hidden text-center">
        <!-- Decoration -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6 relative">
                <svg class="w-10 h-10 text-blue-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="absolute inset-0 border-4 border-blue-400 border-t-transparent rounded-full animate-spin opacity-20"></div>
            </div>
            
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter mb-2">Processing Request</h2>
            <p class="text-gray-400 text-sm font-medium">We are waiting for VTUAfrica to confirm your transfer and fund your wallet.</p>
        </div>

        <div class="bg-white/5 rounded-2xl p-6 border border-white/5 space-y-4 mb-8">
            <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest">
                <span class="text-gray-500">Status</span>
                <span class="px-3 py-1 rounded-full bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">{{ strtoupper($request->status) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest border-t border-white/5 pt-4">
                <span class="text-gray-500">Reference</span>
                <span class="text-blue-400 tracking-tight">{{ $request->reference }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest border-t border-white/5 pt-4">
                <span class="text-gray-500">Amount Sent</span>
                <span class="text-white">₦{{ number_format($request->amount, 2) }}</span>
            </div>
            <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest border-t border-white/5 pt-4">
                <span class="text-gray-500">You will receive</span>
                <span class="text-emerald-400 font-extrabold text-sm">₦{{ number_format($request->payable, 2) }}</span>
            </div>
        </div>

        @if($request->status === 'failed' && $request->admin_note)
            <div class="mb-8 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-left">
                <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-1">Provider Note:</p>
                <p class="text-gray-400 text-xs">{{ $request->admin_note }}</p>
            </div>
        @endif

        <div class="space-y-4">
            <div class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.2em]">
                Auto-refreshing in <span class="text-blue-400" x-text="timer"></span> seconds
            </div>
            
            <div class="flex gap-3">
                <button @click="refresh()" class="flex-1 bg-white/5 hover:bg-white/10 text-white font-bold py-4 rounded-xl transition-all border border-white/5 text-xs uppercase tracking-widest">
                    Refresh Now
                </button>
                <a href="{{ route('dashboard') }}" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-500/25 text-xs uppercase tracking-widest">
                    Go to Dashboard
                </a>
            </div>
        </div>

        <p class="mt-8 text-[10px] text-gray-500 font-medium leading-relaxed italic">
            *Conversion usually takes 1-5 minutes depending on network stability.
        </p>
    </div>
</div>
@endsection
