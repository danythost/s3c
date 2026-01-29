@extends('layouts.app')

@section('title', 'Purchase Airtime')
@section('header', 'Airtime Top-up')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    <!-- Header Section -->
    <div class="text-center space-y-4">
        <h2 class="text-3xl font-black text-white tracking-tight">Purchase Airtime</h2>
        <p class="text-gray-500 text-sm max-w-md mx-auto">Instant airtime recharge for all networks. Enter recipient number and amount below.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Form Section -->
        <div class="lg:col-span-2 space-y-8">
            <form action="{{ route('vtu.airtime.purchase') }}" method="POST" id="airtimePurchaseForm" class="space-y-8">
                @csrf
                
                <!-- Recipient Info -->
                <div class="glass p-8 rounded-[2rem] border-white/5 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center text-xs font-bold">01</div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">Recipient Details</h3>
                    </div>

                    <div>
                        <label for="phone" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Phone Number</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-blue-400 transition-colors">
                                <span>📱</span>
                            </div>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all"
                                   placeholder="08012345678" maxlength="11" required>
                        </div>
                        @error('phone') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Network Selection -->
                <div class="glass p-8 rounded-[2rem] border-white/5 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-bold">02</div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">Select Provider</h3>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4" id="networkSelection">
                        @foreach(['MTN', 'Airtel', 'GLO', '9mobile'] as $networkName)
                            <label class="cursor-pointer group">
                                <input type="radio" name="network" value="{{ $networkName }}" class="peer hidden" required 
                                       {{ old('network') == $networkName ? 'checked' : '' }}>
                                <div class="glass h-24 rounded-2xl border border-white/5 flex flex-col items-center justify-center gap-2 peer-checked:bg-blue-600 peer-checked:border-blue-400 transition-all hover:bg-white/5">
                                    <div class="text-xl group-hover:scale-110 transition-transform">
                                        @if($networkName == 'MTN') 🟡 @elseif($networkName == 'Airtel') 🔴 @elseif($networkName == 'GLO') 🟢 @else 🔵 @endif
                                    </div>
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-white">{{ $networkName }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('network') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
                </div>

                <!-- Amount Selection -->
                <div class="glass p-8 rounded-[2rem] border-white/5 space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center text-xs font-bold">03</div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">Enter Amount</h3>
                    </div>

                    <div>
                        <label for="amount" class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Recharge Amount (₦)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 group-focus-within:text-purple-400 transition-colors">
                                <span>₦</span>
                            </div>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                                   class="w-full bg-white/5 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white font-bold placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500 transition-all"
                                   placeholder="Min: 100" min="100" max="50000" required>
                        </div>
                        @error('amount') <p class="mt-2 text-xs text-red-400 font-bold uppercase tracking-tighter">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-4">
                    <button type="submit" id="submitBtn" 
                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-95 disabled:opacity-30">
                        CONFIRM RECHARGE
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Section -->
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-white tracking-tight">Order Summary</h3>
            <div class="glass p-8 rounded-[2rem] border-white/5 space-y-8 sticky top-24">
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-bold uppercase tracking-widest">Service</span>
                        <span class="text-white font-black">Airtime Top-up</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-bold uppercase tracking-widest">Network</span>
                        <span class="text-white font-black" id="summaryNetwork">{{ old('network') ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-bold uppercase tracking-widest">Recipient</span>
                        <span class="text-white font-black" id="summaryPhone">{{ old('phone') ?: '-' }}</span>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/5">
                    <div class="flex justify-between items-end mb-1">
                        <span class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Total Cost</span>
                        <span class="text-3xl font-black text-emerald-400" id="displayAmount">₦{{ number_format(old('amount') ?: 0, 2) }}</span>
                    </div>
                    <p class="text-[9px] text-gray-600 italic">Funds will be deducted from your wallet balance.</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-emerald-500/70">
                        <span>✓</span> Instant Delivery
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-emerald-500/70">
                        <span>✓</span> 24/7 Availability
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const networkInputs = document.querySelectorAll('input[name="network"]');
        const phoneInput = document.getElementById('phone');
        const amountInput = document.getElementById('amount');
        
        const summaryNetwork = document.getElementById('summaryNetwork');
        const summaryPhone = document.getElementById('summaryPhone');
        const displayAmount = document.getElementById('displayAmount');

        networkInputs.forEach(input => {
            input.addEventListener('change', function() {
                summaryNetwork.textContent = this.value;
                summaryNetwork.classList.add('text-blue-400');
            });
        });

        phoneInput.addEventListener('input', function() {
            summaryPhone.textContent = this.value || '-';
            summaryPhone.classList.toggle('text-blue-400', this.value.length > 0);
        });

        amountInput.addEventListener('input', function() {
            const val = parseFloat(this.value) || 0;
            displayAmount.textContent = `₦${val.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            displayAmount.classList.toggle('text-emerald-400', val > 0);
        });
    });
</script>
@endsection
