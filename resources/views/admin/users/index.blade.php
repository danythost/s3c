@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2">Users</h1>
        <p class="text-gray-400 text-sm">Manage users of all roles and their balances.</p>
    </div>
</div>

<div class="glass rounded-3xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10">
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">User</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Role</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Balance</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Joined</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 border border-blue-500/50 flex items-center justify-center text-blue-400 font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $user->role == 'admin' ? 'bg-indigo-500/20 text-indigo-400' : 'bg-blue-500/20 text-blue-400' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="text-sm font-bold text-white">₦{{ number_format($user->wallet?->balance ?? 0, 2) }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-sm text-gray-400">{{ $user->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="#" class="p-2 hover:bg-white/10 rounded-lg text-blue-400 transition-colors opacity-50 cursor-not-allowed" title="Edit (Coming Soon)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="p-4 border-t border-white/10">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
