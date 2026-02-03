@extends('layouts.app')

@section('title', 'Submit Airtime')

@section('header', 'SEND AIRTIME REQUEST')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="glass rounded-3xl p-8 border-white/10 relative overflow-hidden">
        <h2 class="text-2xl font-bold text-white mb-6">Request Details</h2>
        
        <form action="{{ route('a2c.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div x-data="{ network: '' }">
                <label class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Select Network</label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach(['MTN', 'AIRTEL', 'GLO', '9MOBILE'] as $net)
                        <label class="relative group">
                            <input type="radio" name="network" value="{{ $net }}" class="peer hidden" required x-model="network">
                            <div class="p-4 rounded-2xl border border-white/5 bg-white/5 text-center cursor-pointer transition-all hover:bg-white/10 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 peer-checked:text-blue-400">
                                <span class="font-bold tracking-widest">{{ $net }}</span>
                                <div class="text-[10px] text-gray-500 mt-1 uppercase">{{ number_format(config('airtime2cash.rates.' . $net) * 100) }}% Rate</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('network') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ amount: 0, rate: 0 }" x-init="$watch('network', value => { 
                const rates = {
                    'MTN': {{ config('airtime2cash.rates.MTN') }},
                    'AIRTEL': {{ config('airtime2cash.rates.AIRTEL') }},
                    'GLO': {{ config('airtime2cash.rates.GLO') }},
                    '9MOBILE': {{ config('airtime2cash.rates.9MOBILE') }}
                };
                rate = rates[value] || 0;
            })">
                <label for="amount" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Amount (₦)</label>
                <div class="relative">
                    <input type="number" name="amount" id="amount" required min="{{ config('airtime2cash.min_amount') }}" step="1"
                           x-model="amount"
                           class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all font-bold"
                           placeholder="e.g. 1000">
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-500 font-bold">NGN</div>
                </div>
                @error('amount') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror

                <div class="mt-4 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 flex justify-between items-center" x-show="amount > 0">
                    <span class="text-gray-400 text-sm font-bold uppercase tracking-wider">You will receive:</span>
                    <span class="text-blue-400 font-extrabold text-xl">₦<span x-text="Math.floor(amount * rate).toLocaleString()"></span></span>
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-2">Sender Phone Number</label>
                <input type="text" name="phone" id="phone" required
                       class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all font-bold"
                       placeholder="080 0000 0000">
                <p class="text-[10px] text-gray-500 mt-2 uppercase">Providing the correct number is critical for verification.</p>
                @error('phone') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-500 hover:to-emerald-600 text-white font-bold py-5 rounded-2xl transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-emerald-500/25 uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                Continue to Payment
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </button>
        </form>
    </div>
</div>
@endsection
