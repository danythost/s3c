@extends('layouts.app')

@section('title', 'Success')

@section('header', 'CONVERSION COMPLETED')

@section('content')
<div class="max-w-xl mx-auto text-center">
    <div class="glass rounded-3xl p-12 border-white/10 relative overflow-hidden">
        
        <div class="flex justify-center mb-10">
            <div class="w-32 h-32 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-4xl shadow-lg shadow-emerald-500/20">
                🎉
            </div>
        </div>

        <h2 class="text-3xl font-extrabold text-white mb-2 uppercase tracking-tight">Success!</h2>
        <p class="text-emerald-400 font-bold uppercase tracking-widest text-xs mb-8">Your wallet has been credited</p>

        <div class="bg-white/5 rounded-2xl p-8 border border-white/5 mb-10 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 opacity-5">
                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            
            <span class="block text-gray-500 text-[10px] font-black tracking-[0.2em] uppercase mb-1">Amount Credited</span>
            <div class="text-4xl font-black text-white">₦{{ number_format($request->payable) }}</div>
        </div>

        <div class="flex flex-col gap-4">
            <a href="{{ route('wallet.index') }}" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-bold py-5 rounded-2xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-blue-500/25 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                View Wallet History
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </a>
            
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">Go to Dashboard</a>
        </div>
    </div>
</div>
@endsection
