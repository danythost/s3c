@extends('layouts.admin')

@section('title', 'System Configuration')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">System Configuration</h2>
    
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1" x-data="{ tab: 'providers' }">
        <button @click="tab = 'providers'" 
           :class="tab === 'providers' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            Providers
        </button>
        <button @click="tab = 'pricing'" 
           :class="tab === 'pricing' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            Pricing Rules
        </button>
        <button @click="tab = 'audit'" 
           :class="tab === 'audit' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            Audit Trail
        </button>
        <button @click="tab = 'maintenance'" 
           :class="tab === 'maintenance' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 border-b-2 border-transparent'"
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors">
            Maintenance
        </button>

        <div class="mt-8 w-full">
            <!-- Providers Tab -->
            <div x-show="tab === 'providers'" class="space-y-6">
                @forelse($providers as $provider)
                    <div class="glass p-6 rounded-3xl">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-bold">{{ $provider->name }}</h3>
                                <p class="text-xs text-gray-500">Slug: {{ $provider->slug }}</p>
                            </div>
                            <form action="{{ route('admin.settings.providers.update', $provider) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $provider->name }}">
                                <input type="hidden" name="is_active" value="{{ $provider->is_active ? 0 : 1 }}">
                                <button type="submit" class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase transition-all {{ $provider->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $provider->is_active ? 'Active' : 'Disabled' }}
                                </button>
                            </form>
                        </div>
                        
                        <form action="{{ route('admin.settings.providers.update', $provider) }}" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <input type="hidden" name="name" value="{{ $provider->name }}">
                            <input type="hidden" name="is_active" value="{{ $provider->is_active ? 1 : 0 }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($provider->config ?? [] as $key => $value)
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2">{{ str_replace('_', ' ', $key) }}</label>
                                        <input type="text" name="config[{{ $key }}]" value="{{ $value }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="submit" class="glass px-6 py-2 rounded-xl text-xs font-bold hover:bg-white/10 mt-4">Save Config</button>
                        </form>
                    </div>
                @empty
                    <div class="glass p-10 rounded-3xl text-center text-gray-500">
                        No providers configured.
                    </div>
                @endforelse
            </div>

            <!-- Pricing Tab -->
            <div x-show="tab === 'pricing'" class="space-y-6">
                <div class="glass p-8 rounded-3xl">
                    <h3 class="text-xl font-bold mb-6">Global Pricing Rules</h3>
                    <form action="{{ route('admin.settings.pricing.update') }}" method="POST" class="space-y-6 max-w-lg">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Global Markup (%)</label>
                            <input type="number" step="0.01" name="settings[global_markup]" value="{{ $settings['pricing']['global_markup']->value ?? 0 }}" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none">
                            <p class="text-xs text-gray-500 mt-1">Applied to all services as a default.</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Reseller Discount (%)</label>
                            <input type="number" step="0.01" name="settings[reseller_discount]" value="{{ $settings['pricing']['reseller_discount']->value ?? 0 }}" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-blue-500 outline-none">
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 px-8 py-3 rounded-xl font-bold transition-all">Update Pricing</button>
                    </form>
                </div>
            </div>

            <!-- Audit Tab -->
            <div x-show="tab === 'audit'">
                <div class="glass rounded-3xl overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                                    <th class="p-6 font-bold">Admin</th>
                                    <th class="p-6 font-bold">Action</th>
                                    <th class="p-6 font-bold">Description</th>
                                    <th class="p-6 font-bold">IP Address</th>
                                    <th class="p-6 font-bold">Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($auditLogs as $log)
                                    <tr class="hover:bg-white/10 transition-colors">
                                        <td class="p-6 font-bold text-sm text-white">{{ $log->user->name ?? 'System' }}</td>
                                        <td class="p-6 font-mono text-xs uppercase text-blue-400">{{ $log->action }}</td>
                                        <td class="p-6 text-xs text-gray-400">{{ $log->description }}</td>
                                        <td class="p-6 text-xs text-gray-500">{{ $log->ip_address }}</td>
                                        <td class="p-6 text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-10 text-center text-gray-500">No logs found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Maintenance Tab -->
            <div x-show="tab === 'maintenance'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- System Health -->
                    <div class="glass p-8 rounded-3xl">
                        <h3 class="text-xl font-bold mb-6">System Health</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-2xl">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">PHP Version</span>
                                <span class="text-sm font-mono text-blue-400">{{ $maintenance['system']['php_version'] }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-2xl">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Database</span>
                                <span class="text-sm font-mono text-emerald-400">Connected ({{ basename($maintenance['system']['db_connection']) }})</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-2xl">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Environment</span>
                                <span class="text-sm font-bold text-orange-400">{{ $maintenance['system']['environment'] }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-white/5 rounded-2xl">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Server Time</span>
                                <span class="text-sm text-gray-400">{{ $maintenance['system']['server_time'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Monitor -->
                    <div class="glass p-8 rounded-3xl">
                        <h3 class="text-xl font-bold mb-6">Queue & Background Jobs</h3>
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-2xl">
                                <div class="text-[10px] text-blue-400 uppercase font-bold mb-1">Pending Jobs</div>
                                <div class="text-2xl font-bold">{{ $maintenance['queue']['pending'] }}</div>
                            </div>
                            <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                                <div class="text-[10px] text-red-400 uppercase font-bold mb-1">Failed Jobs</div>
                                <div class="text-2xl font-bold">{{ $maintenance['queue']['failed'] }}</div>
                            </div>
                        </div>

                        @if($maintenance['queue']['failed'] > 0)
                            <div class="flex gap-2">
                                <form action="{{ route('admin.settings.maintenance.failed-jobs') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="retry_all">
                                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-xs font-bold rounded-xl transition-all">Retry All</button>
                                </form>
                                <form action="{{ route('admin.settings.maintenance.failed-jobs') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="delete_all">
                                    <button class="px-4 py-2 bg-red-600/20 text-red-400 hover:bg-red-600/30 text-xs font-bold rounded-xl transition-all border border-red-600/30">Flush All</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cache Control -->
                <div class="glass p-8 rounded-3xl">
                    <h3 class="text-xl font-bold mb-6">Cache Management</h3>
                    <div class="flex flex-wrap gap-4">
                        <form action="{{ route('admin.settings.maintenance.clear-cache') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="application">
                            <button class="glass px-6 py-4 rounded-2xl text-center min-w-[140px] hover:bg-white/10 transition-all">
                                <span class="block text-xs font-bold mb-1">Application Cache</span>
                                <span class="text-[10px] text-gray-500">Flush Redis/File cache</span>
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.settings.maintenance.clear-cache') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="config">
                            <button class="glass px-6 py-4 rounded-2xl text-center min-w-[140px] hover:bg-white/10 transition-all">
                                <span class="block text-xs font-bold mb-1">Config Cache</span>
                                <span class="text-[10px] text-gray-500">Refresh .env & configs</span>
                            </button>
                        </form>

                        <form action="{{ route('admin.settings.maintenance.clear-cache') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="view">
                            <button class="glass px-6 py-4 rounded-2xl text-center min-w-[140px] hover:bg-white/10 transition-all">
                                <span class="block text-xs font-bold mb-1">View Cache</span>
                                <span class="text-[10px] text-gray-500">Recompile blade templates</span>
                            </button>
                        </form>

                        <form action="{{ route('admin.settings.maintenance.clear-cache') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="all">
                            <button class="bg-white/10 px-6 py-4 rounded-2xl text-center min-w-[140px] hover:bg-white/20 transition-all border border-white/10">
                                <span class="block text-xs font-bold mb-1 text-white">Optimize & Clear</span>
                                <span class="text-[10px] text-gray-400">Run optimize:clear</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
