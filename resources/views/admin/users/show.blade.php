@extends('layouts.admin')

@section('title', 'User Profile')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ url()->previous() }}" class="glass p-2 rounded-xl text-gray-400 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold">{{ $user instanceof \App\Models\Admin ? 'Admin Profile' : 'User Profile' }}</h2>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        @if($user instanceof \App\Models\User)
        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-6 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 {{ $user->is_active ? 'bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white' }}">
                @if($user->is_active)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    Suspend User
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activate User
                @endif
            </button>
        </form>
        @endif

        <form action="{{ $user instanceof \App\Models\Admin ? route('admin.admins.destroy', $user->id) : route('admin.users.destroy', $user->id) }}" method="POST" id="deleteUserForm">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmDelete()" class="px-6 py-2 rounded-xl text-sm font-bold bg-gray-500/10 text-gray-400 hover:bg-red-600 hover:text-white transition-all flex items-center gap-2 border border-white/5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Account
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="space-y-6">
        <div class="glass p-6 rounded-2xl text-center">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 mx-auto flex items-center justify-center text-3xl font-bold text-white mb-4">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold text-white">{{ $user->name }}</h3>
            <p class="text-sm text-gray-400 mb-4">{{ $user->email }}</p>
            
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 text-xs font-bold text-gray-300">
                <span class="w-2 h-2 rounded-full {{ ($user instanceof \App\Models\Admin || $user->is_active) ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                {{ ($user instanceof \App\Models\Admin || $user->is_active) ? 'Active Account' : 'Suspended' }}
            </div>
        </div>

        @if($user instanceof \App\Models\User)
        <div class="glass p-6 rounded-2xl">
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Wallet Balance</h4>
            <h2 class="text-3xl font-bold text-white mb-1">₦{{ number_format($user->wallet->balance ?? 0, 2) }}</h2>
            <p class="text-xs text-gray-500">Available Funds</p>
        </div>
        @endif

        <div class="glass p-6 rounded-2xl">
            <h4 class="text-sm font-bold text-gray-400 uppercase mb-4">Account Info</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Role</span>
                    <span class="text-white capitalize">{{ $user->role ?? 'User' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Joined</span>
                    <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Last Login</span>
                    <span class="text-white">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity -->
    <div class="lg:col-span-2 space-y-6">
        @if($user instanceof \App\Models\User)
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-6">Recent Transactions</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] uppercase text-gray-500 border-b border-white/10">
                            <th class="pb-3 pl-2">Reference</th>
                            <th class="pb-3">Source</th>
                            <th class="pb-3">Type</th>
                            <th class="pb-3">Amount</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($user->transactions as $transaction)
                            <tr class="text-sm group hover:bg-white/5 transition-colors">
                                <td class="py-3 pl-2 font-mono text-blue-400">
                                    {{ substr($transaction->reference, 0, 8) }}...
                                </td>
                                <td class="py-3 uppercase text-xs">{{ $transaction->source }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold {{ $transaction->type === 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' }}">
                                        {{ $transaction->type }}
                                    </span>
                                </td>
                                <td class="py-3 font-bold">₦{{ number_format($transaction->amount, 2) }}</td>
                                <td class="py-3">
                                    <span class="text-[10px] uppercase font-bold {{ $transaction->status === 'success' || $transaction->status === 'completed' ? 'text-emerald-400' : ($transaction->status === 'failed' ? 'text-red-400' : 'text-amber-400') }}">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-400 text-xs">{{ $transaction->created_at->format('M d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500 text-xs">No recent transactions.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="glass p-12 rounded-3xl flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-400 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Administrator Account</h3>
            <p class="text-gray-400 max-w-sm">This is a system administrator account. Administrators have full access to the platform management features.</p>
        </div>
        @endif

        <!-- Login Logs (Mock) -->
        <div class="glass p-6 rounded-2xl">
            <h3 class="text-lg font-bold mb-4">Login Activity</h3>
            @if($user->last_login_at)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white">Last Successful Login</p>
                        <p class="text-xs text-gray-400">{{ $user->last_login_at->format('l, F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm">No login history available.</p>
            @endif
        </div>
    </div>
</div>
<script>
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action is permanent and will delete all user data!",
            icon: 'warning',
            showCancelButton: true,
            background: '#1a1a2e',
            color: '#fff',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-3xl border border-white/10 glass shadow-2xl',
                title: 'text-2xl font-black tracking-tight mb-4',
                confirmButton: 'rounded-xl px-8 py-3 font-bold uppercase tracking-widest text-xs',
                cancelButton: 'rounded-xl px-8 py-3 font-bold uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteUserForm').submit();
            }
        });
    }
</script>
@endsection
