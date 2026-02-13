@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Welcome back, ' . auth()->user()->name)

@section('content')
<div class="space-y-10">
    <!-- Announcements Area -->
    @if(isset($activeAnnouncements) && $activeAnnouncements->count() > 0)
        <div class="space-y-4">
            @foreach($activeAnnouncements as $announcement)
                @php
                    $colors = [
                        'info' => 'from-blue-500/10 to-blue-600/10 text-blue-400 border-blue-500/20',
                        'warning' => 'from-amber-500/10 to-amber-600/10 text-amber-400 border-amber-500/20',
                        'danger' => 'from-red-500/10 to-red-600/10 text-red-400 border-red-500/20',
                        'success' => 'from-emerald-500/10 to-emerald-600/10 text-emerald-400 border-emerald-500/20',
                    ];
                    $colorClass = $colors[$announcement->type] ?? $colors['info'];
                @endphp
                <div class="glass bg-gradient-to-r {{ $colorClass }} p-6 rounded-3xl border flex items-start gap-4 transition-all hover:bg-white/5 group">
                    <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center text-xl shrink-0">
                        @if($announcement->type == 'warning') ⚠️ @elseif($announcement->type == 'danger') 🚫 @elseif($announcement->type == 'success') ✅ @else 📢 @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-sm tracking-tight mb-1">{{ $announcement->title }}</h4>
                        <p class="text-xs opacity-70 leading-relaxed">{{ $announcement->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <!-- Hero / Balances -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass bg-gradient-to-br from-blue-600/20 to-emerald-600/20 p-8 rounded-[2rem] border-white/10 relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            
            <div class="relative flex flex-col md:flex-row justify-between items-center gap-8">
                <div>
                    <span class="text-xs font-bold text-blue-400 uppercase tracking-[0.2em] mb-2 block">Personal Balance</span>
                    <h2 class="text-5xl font-black text-white tracking-tighter">₦ {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</h2>
                    <p class="text-sm text-gray-400 mt-4 max-w-xs leading-relaxed">Your funds are ready for instant top-ups across all available networks and services.</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('wallet.index') }}" class="bg-white text-gray-900 px-8 py-4 rounded-2xl font-bold text-sm shadow-xl hover:bg-gray-100 transition-all transform hover:scale-105">
                        Add Funds
                    </a>
                </div>
            </div>
        </div>

        <div class="glass p-8 rounded-[2rem] border-white/5 flex flex-col justify-center">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 block">Usage Stats</span>
            <div class="flex items-end gap-3">
                <span class="text-4xl font-black text-white">{{ $stats['total_activity'] }}</span>
                <span class="text-sm text-gray-500 mb-1 font-bold italic">Total Activity</span>
            </div>
            <div class="mt-6 h-1 w-full bg-white/5 rounded-full overflow-hidden" title="Monthly Success Rate: {{ number_format($stats['success_rate']) }}%">
                <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $stats['success_rate'] }}%"></div>
            </div>
            <div class="flex justify-between items-center mt-3">
                <p class="text-[10px] text-gray-500 uppercase font-black">Success Rate</p>
                <p class="text-[10px] text-emerald-400 font-black">{{ number_format($stats['success_rate']) }}%</p>
            </div>
            <div class="flex justify-between items-center mt-1">
                <p class="text-[10px] text-gray-500 uppercase font-black">Monthly Vol.</p>
                <p class="text-[10px] text-blue-400 font-black">₦{{ number_format($stats['monthly_volume']) }}</p>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-10">
        <!-- Quick Services -->
        <div class="xl:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white tracking-tight">Available Services</h3>
                <span class="text-xs text-gray-500 font-bold uppercase tracking-widest">Instant Delivery</span>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @php
                    $services = [
                        ['name' => 'Data Bundle', 'icon' => '📶', 'desc' => 'MTN, GLO, Airtel...', 'route' => 'vtu.data.index', 'color' => 'blue'],
                        ['name' => 'Airtime', 'icon' => '📞', 'desc' => 'Instant Top-up', 'route' => 'vtu.airtime.index', 'color' => 'emerald'],
                        ['name' => 'Settings', 'icon' => '⚙️', 'desc' => 'Manage Profile', 'route' => '#', 'color' => 'gray'],
                    ];
                @endphp

                @foreach($services as $service)
                    <a href="{{ $service['route'] !== '#' ? route($service['route']) : '#' }}" class="glass p-6 rounded-3xl border-white/5 hover:bg-white/10 hover:border-white/20 transition-all group overflow-hidden relative">
                        <div class="absolute -bottom-4 -right-4 text-6xl opacity-[0.03] group-hover:opacity-[0.07] transition-opacity">{{ $service['icon'] }}</div>
                        <div class="w-12 h-12 rounded-2xl bg-{{ $service['color'] }}-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                            {{ $service['icon'] }}
                        </div>
                        <h4 class="font-bold text-white text-sm">{{ $service['name'] }}</h4>
                        <p class="text-[10px] text-gray-500 mt-1 uppercase font-bold tracking-tighter">{{ $service['desc'] }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Right Side: Recent Activity -->
        <div class="space-y-6">
            <h3 class="text-xl font-bold text-white tracking-tight">Recent Activity</h3>
            <div class="space-y-3">
                @forelse($activities as $activity)
                    <div class="flex items-center justify-between p-4 glass rounded-[1.5rem] border-white/5 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-lg">
                                @if($activity->source === 'wallet_funding')
                                    💰
                                @elseif($activity->source === 'data')
                                    📶
                                @elseif($activity->source === 'airtime')
                                    📞
                                @elseif($activity->source === 'vtuafrica_a2c')
                                    🔄
                                @else
                                    💳
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">
                                    {{ ucfirst(str_replace(['_', '-'], ' ', $activity->source)) }}
                                    <span class="ml-2 px-2 py-0.5 rounded-full text-[8px] uppercase font-bold {{ $activity->type === 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ $activity->type }}
                                    </span>
                                </p>
                                <p class="text-[10px] text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-white">₦{{ number_format($activity->amount, 2) }}</p>
                            <span class="text-[9px] uppercase font-black {{ $activity->status == 'completed' || $activity->status == 'success' ? 'text-emerald-400' : ($activity->status == 'pending' ? 'text-yellow-500' : 'text-red-400') }}">
                                {{ $activity->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 glass rounded-[2rem] border-white/5">
                        <p class="text-gray-500 italic text-sm">No transactions yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
