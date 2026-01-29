@extends('layouts.app')

@section('title', 'Select Data Plan')
@section('header', 'Data Bundle')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    <!-- Header Section -->
    <div class="text-center space-y-4">
        <h2 class="text-3xl font-black text-white tracking-tight">Purchase Data Bundle</h2>
        <p class="text-gray-500 text-sm max-w-md mx-auto">Get instant data top-up for yourself or loved ones. Select your network and preferred plan below.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Form Section -->
        <div class="lg:col-span-2 space-y-8">
            <form action="{{ route('vtu.data.purchase') }}" method="POST" id="dataPurchaseForm" class="space-y-8">
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
                            @php $networkKey = strtoupper($networkName); @endphp
                            <label class="cursor-pointer group">
                                <input type="radio" name="network" value="{{ $networkName }}" class="peer hidden" required 
                                       {{ old('network') == $networkName ? 'checked' : '' }}
                                       {{ !isset($plans[$networkKey]) ? 'disabled' : '' }}>
                                <div class="glass h-24 rounded-2xl border border-white/5 flex flex-col items-center justify-center gap-2 peer-checked:bg-blue-600 peer-checked:border-blue-400 transition-all hover:bg-white/5 peer-disabled:opacity-20 peer-disabled:cursor-not-allowed">
                                    <div class="text-xl group-hover:scale-110 transition-transform">
                                        @if($networkName == 'MTN') 🟡 @elseif($networkName == 'Airtel') 🔴 @elseif($networkName == 'GLO') 🟢 @else 🔵 @endif
                                    </div>
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-white">{{ $networkName }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Plan Selection -->
                <div id="planContainer" class="glass p-8 rounded-[2rem] border-white/5 space-y-6 {{ old('network') ? '' : 'hidden' }}">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-purple-500/20 text-purple-400 flex items-center justify-center text-xs font-bold">03</div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-widest">Choose Data Bundle</h3>
                    </div>

                    <div class="relative">
                        <select name="plan_id" id="plan_id" required
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white font-bold appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all cursor-pointer">
                            <option value="" disabled selected>Select a bundle plan</option>
                            @if(old('network') && isset($plans[strtoupper(old('network'))]))
                                @foreach($plans[strtoupper(old('network'))] as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }} data-amount="{{ $plan->selling_price }}">
                                        {{ $plan->name }} — ₦{{ number_format($plan->selling_price, 2) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-500">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>

                <div class="px-4">
                    <button type="submit" id="submitBtn" 
                            class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black py-5 rounded-2xl shadow-2xl shadow-blue-500/20 transition-all transform hover:scale-[1.02] active:scale-95 disabled:opacity-30">
                        CONFIRM PURCHASE
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
                        <span class="text-white font-black">Data Purchase</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-bold uppercase tracking-widest">Network</span>
                        <span class="text-white font-black" id="summaryNetwork">-</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-gray-500 font-bold uppercase tracking-widest">Recipient</span>
                        <span class="text-white font-black" id="summaryPhone">-</span>
                    </div>
                </div>

                <div class="pt-8 border-t border-white/5">
                    <div class="flex justify-between items-end mb-1">
                        <span class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Total Cost</span>
                        <span class="text-3xl font-black text-blue-400" id="planAmount">₦0.00</span>
                    </div>
                    <p class="text-[9px] text-gray-600 italic">Funds will be deducted from your wallet balance.</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-emerald-500/70">
                        <span>✓</span> Instant processing
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
        const plansData = @json($plans);

        const networkInputs = document.querySelectorAll('input[name="network"]');
        const planContainer = document.getElementById('planContainer');
        const planSelect = document.getElementById('plan_id');
        const phoneInput = document.getElementById('phone');
        
        const summaryNetwork = document.getElementById('summaryNetwork');
        const summaryPhone = document.getElementById('summaryPhone');
        const planAmountSpan = document.getElementById('planAmount');

        networkInputs.forEach(input => {
            input.addEventListener('change', function() {
                const network = this.value.toUpperCase();
                const plans = plansData[network] || [];
                
                summaryNetwork.textContent = this.value;
                summaryNetwork.classList.add('text-blue-400');
                
                planSelect.innerHTML = '<option value="" disabled selected>Select a bundle plan</option>';
                plans.forEach(plan => {
                    const option = document.createElement('option');
                    option.value = plan.id;
                    option.textContent = `${plan.name} — ₦${parseFloat(plan.selling_price).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                    option.dataset.amount = plan.selling_price;
                    planSelect.appendChild(option);
                });

                planContainer.classList.remove('hidden');
                planAmountSpan.textContent = '₦0.00';
            });
        });

        planSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const amount = parseFloat(selectedOption.dataset.amount);
                planAmountSpan.textContent = `₦${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
            }
        });

        phoneInput.addEventListener('input', function() {
            summaryPhone.textContent = this.value || '-';
            summaryPhone.classList.toggle('text-blue-400', this.value.length > 0);
        });

        const dataPurchaseForm = document.getElementById('dataPurchaseForm');
        const submitBtn = document.getElementById('submitBtn');

        dataPurchaseForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> PROCESSING...</span>';
        });
    });
</script>
@endsection
