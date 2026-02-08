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

            <div class="flex flex-col sm:flex-row gap-4" x-data="{ open: false, loaded: false }">
                <button @click="open = true; loaded = false" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-500 hover:to-blue-600 text-white font-bold py-4 px-10 rounded-2xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-blue-500/25 uppercase tracking-widest text-xs">
                    Get Started Now
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </button>

                <!-- Teleport Modal to Body for true full-screen overlay -->
                <template x-teleport="body">
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-[9999] flex items-center justify-center"
                         style="display: none;"
                         @keydown.escape.window="open = false">
                        
                        <!-- Backdrop -->
                        <div class="absolute inset-0 bg-gray-900/98 backdrop-blur-2xl" @click="open = false"></div>

                        <!-- Modal Content -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-100 translate-y-8"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-100 translate-y-8"
                             class="relative w-full h-full bg-[#0f172a] overflow-hidden flex flex-col">
                            
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-4 sm:p-6 border-b border-white/5 bg-[#1a1a2e] shadow-2xl relative z-20">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-black tracking-tight text-xl uppercase italic">VTUAfrica Portal</h3>
                                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-[0.4em] mt-0.5">Secure Tunnel • High Speed Conversion</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div x-show="!loaded" class="hidden lg:flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-widest border border-blue-500/20 animate-pulse">
                                        <div class="w-2 h-2 rounded-full bg-blue-400 animate-bounce"></div>
                                        Establishing Link...
                                    </div>
                                    <a href="https://vtuafrica.com.ng/portal/" target="_blank" class="flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[11px] font-black uppercase transition-all shadow-xl active:scale-95 border border-white/10">
                                        <span>Direct Link</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    </a>
                                    <button @click="open = false" class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-red-500/30 transition-all border border-white/10 group">
                                        <svg class="w-9 h-9 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Info Banner -->
                            <div class="bg-blue-600/10 border-b border-blue-500/20 px-8 py-3 flex items-center justify-center gap-4 relative z-10">
                                <span class="animate-bounce">💡</span>
                                <p class="text-[11px] text-blue-200 font-black uppercase tracking-widest leading-loose">
                                    Session Hint: If you remain on the login page after sign-in, use the <span class="text-white underline">"Direct Link"</span> button above.
                                </p>
                            </div>

                            <!-- Iframe Content -->
                            <div class="flex-1 overflow-hidden relative bg-white">
                                <template x-if="open">
                                    <div class="w-full h-full">
                                        <!-- Better Loading Spinner -->
                                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-[#0a0f1a] z-50 transition-opacity duration-1000" x-show="!loaded" x-transition:leave="opacity-0">
                                            <div class="relative w-32 h-32">
                                                <div class="absolute inset-0 border-[6px] border-blue-500/5 rounded-full"></div>
                                                <div class="absolute inset-0 border-[6px] border-t-blue-500 rounded-full animate-spin"></div>
                                                <div class="absolute inset-4 border-[6px] border-emerald-500/5 rounded-full"></div>
                                                <div class="absolute inset-4 border-[6px] border-b-emerald-500 rounded-full animate-[spin_2s_linear_infinite]"></div>
                                            </div>
                                            <div class="mt-12 text-center">
                                                <h4 class="text-white text-lg font-black uppercase tracking-[0.5em] animate-pulse">Initializing Portal</h4>
                                                <p class="text-gray-500 text-[10px] mt-4 font-black uppercase tracking-[0.3em]">Bypassing Cross-Origin Restrictions...</p>
                                            </div>
                                        </div>
                                        
                                        <iframe src="https://vtuafrica.com.ng/portal/" 
                                                class="w-full h-full border-0 absolute inset-0 bg-white" 
                                                allow="payment; clipboard-write; storage-access"
                                                sandbox="allow-forms allow-modals allow-popups allow-popups-to-escape-sandbox allow-scripts allow-same-origin"
                                                @load="loaded = true"></iframe>
                                    </div>
                                </template>
                            </div>

                            <!-- Modal Footer -->
                            <div class="p-6 bg-[#1a1a2e] border-t border-white/5 flex justify-between items-center px-12 relative z-20">
                                <div class="flex flex-col">
                                    <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.4em]">Infrastructure by VTUAfrica</p>
                                    <p class="text-[9px] text-emerald-500/50 font-black uppercase tracking-[0.2em] mt-1">S3C Secure Portal Wrapper v2.4</p>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-emerald-500/5 border border-emerald-500/10">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                        <span class="text-[10px] text-emerald-500 font-black uppercase tracking-widest">End-to-End Encryption</span>
                                    </div>
                                    <div class="flex -space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-800 border-2 border-[#1a1a2e] flex items-center justify-center text-[10px]">🔒</div>
                                        <div class="w-8 h-8 rounded-full bg-gray-800 border-2 border-[#1a1a2e] flex items-center justify-center text-[10px]">🛡️</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
