@extends('layouts.app')

@section('title', 'Select Data Plan')
@section('header', 'Data Bundle')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass p-8 rounded-2xl shadow-2xl relative overflow-hidden group">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
        
        <form action="{{ route('vtu.data.purchase') }}" method="POST" id="dataPurchaseForm">
            @csrf
            
            <div class="space-y-6">
                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-400 mb-2">Recipient Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all"
                           placeholder="08012345678" maxlength="11" required>
                    @error('phone') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Network Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-4">Select Network</label>
                    <div class="grid grid-cols-4 gap-4" id="networkSelection">
                        @foreach(['MTN', 'Airtel', 'GLO', '9mobile'] as $network)
                            <label class="cursor-pointer">
                                <input type="radio" name="network" value="{{ $network }}" class="peer hidden" required {{ old('network') == $network ? 'checked' : '' }}>
                                <div class="glass p-3 rounded-xl border border-white/10 text-center peer-checked:bg-blue-500/20 peer-checked:border-blue-500 transition-all hover:bg-white/10 grayscale peer-checked:grayscale-0">
                                    <span class="block text-xs font-bold">{{ $network }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Plan Selection -->
                <div id="planContainer" class="hidden">
                    <label for="plan_id" class="block text-sm font-medium text-gray-400 mb-2">Select Data Plan</label>
                    <select name="plan_id" id="plan_id" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all appearance-none">
                        <option value="" disabled selected>Select a plan</option>
                    </select>
                    @error('plan_id') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Hidden Amount -->
                <input type="hidden" name="amount" id="selectedAmount">

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" 
                        class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/25 transition-all transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    Continue to Confirmation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const networks = {
            'MTN': [
                { id: 'mtn-1gb', name: '1GB (Monthly)', amount: 300 },
                { id: 'mtn-2gb', name: '2GB (Monthly)', amount: 600 }
            ],
            'Airtel': [
                { id: 'airtel-1gb', name: '1GB (Monthly)', amount: 280 },
                { id: 'airtel-2gb', name: '2GB (Monthly)', amount: 560 }
            ]
        };

        const networkInputs = document.querySelectorAll('input[name="network"]');
        const planContainer = document.getElementById('planContainer');
        const planSelect = document.getElementById('plan_id');
        const amountInput = document.getElementById('selectedAmount');

        networkInputs.forEach(input => {
            input.addEventListener('change', function() {
                const network = this.value;
                const plans = networks[network] || [];
                
                planSelect.innerHTML = '<option value="" disabled selected>Select a plan</option>';
                plans.forEach(plan => {
                    const option = document.createElement('option');
                    option.value = plan.id;
                    option.textContent = `${plan.name} - ₦${plan.amount}`;
                    option.dataset.amount = plan.amount;
                    planSelect.appendChild(option);
                });

                planContainer.classList.remove('hidden');
            });
        });

        planSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            amountInput.value = selectedOption.dataset.amount;
        });
    });
</script>
@endsection
