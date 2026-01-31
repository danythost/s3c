@extends('layouts.admin')

@section('title', 'VTU Data Plans')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8" x-data="{ showImport: false, showAdd: false }">
    <div>
        <h2 class="text-2xl font-bold mb-1">VTU Services</h2>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('admin.vtu.plans') }}" class="text-blue-400 font-bold border-b-2 border-blue-400 pb-1">Data Plans</a>
            <a href="{{ route('admin.vtu.airtime') }}" class="text-gray-400 hover:text-white transition-colors pb-1">Airtime Control</a>
        </div>
    </div>
    
    <div class="flex flex-wrap gap-2">
        <button @click="showImport = true" class="glass px-4 py-2 rounded-xl text-xs font-bold hover:bg-white/10 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Import CSV
        </button>
        <button @click="showAdd = true" class="bg-blue-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-500 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Add Plan
        </button>
    </div>

    <!-- Import Modal -->
    <div x-show="showImport" style="display: none" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         x-transition.opacity>
        <div @click.away="showImport = false" class="glass w-full max-w-md p-6 rounded-2xl bg-[#0f172a]">
            <h3 class="text-lg font-bold mb-4">Import Data Plans (CSV)</h3>
            <p class="text-xs text-gray-400 mb-4">
                Format: Network, Provider, Name, Code, ProviderPrice, SellingPrice, Validity.
                <br>Updates existing plans if code matches.
            </p>
            <form action="{{ route('admin.vtu.plans.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file" accept=".csv,.txt" required class="block w-full text-sm text-gray-400
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-xs file:font-semibold
                    file:bg-blue-500/10 file:text-blue-400
                    hover:file:bg-blue-500/20 mb-6">
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showImport = false" class="px-4 py-2 rounded-lg hover:bg-white/5 text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-xs font-bold">Import</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="showAdd" style="display: none" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         x-transition.opacity>
        <div @click.away="showAdd = false" class="glass w-full max-w-lg p-6 rounded-2xl bg-[#0f172a] max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold mb-4">Add New Data Plan</h3>
            <form action="{{ route('admin.vtu.plans.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Network</label>
                        <select name="network" class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm focus:border-blue-500 outline-none">
                            <option value="MTN">MTN</option>
                            <option value="GLO">GLO</option>
                            <option value="AIRTEL">AIRTEL</option>
                            <option value="9MOBILE">9MOBILE</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Provider ID</label>
                        <input type="text" name="provider" value="epins" class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-400 block mb-1">Plan Name</label>
                    <input type="text" name="name" required placeholder="e.g. MTN 1GB SME" class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm outline-none">
                </div>
                <div>
                    <label class="text-xs text-gray-400 block mb-1">Plan Code</label>
                    <input type="text" name="code" required placeholder="Unique Code" class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Provider Price</label>
                        <input type="number" step="0.01" name="provider_price" required class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 block mb-1">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" required class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-400 block mb-1">Validity</label>
                    <input type="text" name="validity" value="30 days" class="w-full bg-white/5 border border-white/10 rounded-lg p-2 text-sm outline-none">
                </div>
                
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="showAdd = false" class="px-4 py-2 rounded-lg hover:bg-white/5 text-xs font-bold">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-xs font-bold">Create Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <select name="network" onchange="this.form.submit()" class="bg-[#0f172a] glass px-4 py-2 rounded-xl text-xs font-bold outline-none border border-transparent focus:border-blue-500 transition-all">
            <option value="">All Networks</option>
            @foreach(['MTN', 'GLO', 'AIRTEL', '9MOBILE'] as $net)
                <option value="{{ $net }}" {{ request('network') == $net ? 'selected' : '' }}>{{ $net }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search plans..." class="bg-[#0f172a] glass px-4 py-2 rounded-xl text-xs outline-none border border-transparent focus:border-blue-500 transition-all w-64">
        <button type="submit" class="glass px-4 py-2 rounded-xl text-xs font-bold hover:bg-white/10">Filter</button>
        @if(request()->anyFilled(['network', 'search']))
            <a href="{{ route('admin.vtu.plans') }}" class="px-4 py-2 text-xs text-red-400 hover:text-red-300">Clear</a>
        @endif
    </form>
</div>

<div class="glass rounded-3xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                    <th class="p-6 font-bold">Network</th>
                    <th class="p-6 font-bold">Plan Name</th>
                    <th class="p-6 font-bold">API Price</th>
                    <th class="p-6 font-bold w-48">Selling Price</th>
                    <th class="p-6 font-bold">Status</th>
                    <th class="p-6 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($plans as $plan)
                    <tr class="hover:bg-white/5 transition-colors group">
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-lg bg-white/5 text-xs font-bold">{{ $plan->network }}</span>
                        </td>
                        <td class="p-6">
                            <p class="text-sm font-bold text-white">{{ $plan->name }}</p>
                            <p class="text-[10px] text-gray-500 font-mono">{{ $plan->code }}</p>
                        </td>
                        <td class="p-6 text-sm font-mono text-gray-400">₦{{ number_format($plan->provider_price, 2) }}</td>
                        <td class="p-6">
                            <form action="{{ route('admin.vtu.plans.update-price', $plan) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PATCH')
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs text-sm">₦</span>
                                    <input type="number" step="0.01" name="selling_price" value="{{ $plan->selling_price }}" 
                                           class="w-32 bg-[#0f172a] border border-white/10 rounded-lg pl-6 pr-3 py-2 text-sm focus:outline-none focus:border-blue-500 transition-all">
                                </div>
                                <button type="submit" class="p-2 rounded-lg bg-blue-500/10 text-blue-400 hover:bg-blue-500 text-white transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </form>
                        </td>
                        <td class="p-6">
                            @if($plan->is_active)
                                <span class="flex items-center gap-1.5 text-emerald-400 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-red-400 text-xs font-bold">
                                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center">
                                <form action="{{ route('admin.vtu.plans.toggle-status', $plan) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-md border {{ $plan->is_active ? 'border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white' : 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-500 hover:text-white' }} transition-all">
                                        {{ $plan->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-white/10">
        {{ $plans->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection
