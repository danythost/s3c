@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">User Management</h2>
    
    <!-- Status Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1">
        <a href="{{ route('admin.users.index') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ !request('status') ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400' }}">
            All Users
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'active']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'active' ? 'text-emerald-400 border-b-2 border-emerald-400' : 'text-gray-400' }}">
            Active Users
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'suspended']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'suspended' ? 'text-red-400 border-b-2 border-red-400' : 'text-gray-400' }}">
            Suspended Users
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'admins']) }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors {{ request('status') == 'admins' ? 'text-purple-400 border-b-2 border-purple-400' : 'text-gray-400' }}">
            Admins
        </a>
    </div>
</div>

<!-- Search -->
<div class="mb-6 flex justify-end">
    <form method="GET" class="flex gap-2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Email..." 
               class="bg-[#0f172a] glass px-4 py-2 rounded-xl text-xs outline-none border border-transparent focus:border-blue-500 transition-all w-64">
        <button type="submit" class="glass px-4 py-2 rounded-xl text-xs font-bold hover:bg-white/10">Search</button>
    </form>
</div>

<!-- Table -->
<div class="glass rounded-3xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                    <th class="p-6 font-bold">User</th>
                    <th class="p-6 font-bold">Role</th>
                    <th class="p-6 font-bold">Wallet Balance</th>
                    <th class="p-6 font-bold">Status</th>
                    <th class="p-6 font-bold">Joined</th>
                    <th class="p-6 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                    <tr class="hover:bg-white/5 transition-colors group cursor-pointer" onclick="window.location='{{ route('admin.users.show', $user) }}'">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 font-bold text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ $user->name }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            @if($user->isAdmin() || $user->role === 'admin')
                                <span class="px-2 py-1 rounded bg-purple-500/10 text-purple-400 text-[10px] font-bold uppercase">Admin</span>
                            @else
                                <span class="px-2 py-1 rounded bg-gray-500/10 text-gray-400 text-[10px] font-bold uppercase">User</span>
                            @endif
                        </td>
                        <td class="p-6 font-mono font-bold text-sm">₦{{ number_format($user->wallet?->balance ?? 0, 2) }}</td>
                        <td class="p-6">
                            @if($user->is_active)
                                <span class="flex items-center gap-1.5 text-emerald-400 text-[10px] font-bold uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-red-400 text-[10px] font-bold uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span> Suspended
                                </span>
                            @endif
                        </td>
                        <td class="p-6 text-xs text-gray-400 py-6">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="p-6 text-center">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-xs font-bold text-blue-400 hover:text-blue-300">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-white/10">
        {{ $users->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection
