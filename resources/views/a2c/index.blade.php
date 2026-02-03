@extends('layouts.app')

@section('title', 'Airtime to Cash')

@section('header', 'CONVERT AIRTIME TO CASH')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="glass rounded-3xl p-8 border-white/10 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>

        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-white mb-2">Airtime to Cash</h1>
            <p class="text-gray-400 mb-8 max-w-2xl">Convert your excess mobile airtime to real cash in your wallet. Fast, secure, and reliable.</p>

            <div class="grid md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/20 flex items-center justify-center text-blue-400 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white uppercase tracking-wider text-sm">Instant Processing</h3>
                            <p class="text-gray-400 text-sm">Your request is processed within 5 to 30 minutes of confirmation.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white uppercase tracking-wider text-sm">Best Rates</h3>
                            <p class="text-gray-400 text-sm">We offer the most competitive conversion rates in the market.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                        <span class="text-yellow-400 text-xl">⚠️</span> Guidelines
                    </h3>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 mt-1">•</span>
                            <span>Minimum amount per transaction is ₦{{ number_format(config('airtime2cash.min_amount')) }}.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 mt-1">•</span>
                            <span>You must send the airtime from the phone number provided.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 mt-1">•</span>
                            <span>Only send the exact amount requested.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-400 mt-1">•</span>
                            <span>Transfer fees charged by your network provider are your responsibility.</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('a2c.create') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-bold py-4 px-10 rounded-2xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-blue-500/25 uppercase tracking-widest text-xs">
                    Get Started Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
