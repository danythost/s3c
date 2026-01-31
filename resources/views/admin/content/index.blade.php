@extends('layouts.admin')

@section('title', 'Content Management')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Content & Communication</h2>
    
    <!-- Tabs -->
    <div class="flex flex-wrap gap-2 text-sm font-medium border-b border-white/10 pb-1">
        <a href="{{ route('admin.content.index') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-blue-400 border-b-2 border-blue-400">
            Announcements
        </a>
        <a href="{{ route('admin.content.logs') }}" 
           class="px-4 py-2 rounded-t-lg hover:bg-white/5 transition-colors text-gray-400 border-b-2 border-transparent">
            Communication Logs
        </a>
    </div>
</div>

<!-- Actions -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-lg font-bold">System Announcements</h3>
        <p class="text-xs text-gray-500">Manage notices, maintenance alerts, and promo banners.</p>
    </div>
    <button onclick="document.getElementById('addAnnouncementModal').showModal()" class="glass px-6 py-2 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white transition-all">
        Add Announcement
    </button>
</div>

<!-- Table -->
<div class="glass rounded-3xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 uppercase text-[10px] tracking-wider text-gray-400">
                    <th class="p-6 font-bold">Type</th>
                    <th class="p-6 font-bold">Title</th>
                    <th class="p-6 font-bold">Message</th>
                    <th class="p-6 font-bold">Active</th>
                    <th class="p-6 font-bold">Duration</th>
                    <th class="p-6 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($announcements as $item)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-6">
                            <span class="px-2 py-1 rounded text-[10px] uppercase font-bold 
                                {{ $item->type == 'info' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $item->type == 'warning' ? 'bg-amber-500/20 text-amber-400' : '' }}
                                {{ $item->type == 'danger' ? 'bg-red-500/20 text-red-400' : '' }}
                                {{ $item->type == 'success' ? 'bg-emerald-500/20 text-emerald-400' : '' }}">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td class="p-6 font-bold text-sm text-white">{{ $item->title }}</td>
                        <td class="p-6 text-xs text-gray-400 max-w-xs truncate">{{ $item->message }}</td>
                        <td class="p-6">
                            <form action="{{ route('admin.content.announcements.toggle', $item) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-10 h-5 rounded-full relative transition-colors {{ $item->is_active ? 'bg-blue-500' : 'bg-gray-700' }}">
                                    <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transition-transform {{ $item->is_active ? 'translate-x-5' : '' }}"></span>
                                </button>
                            </form>
                        </td>
                        <td class="p-6 text-[10px] text-gray-500">
                            @if($item->start_at || $item->end_at)
                                {{ $item->start_at?->format('M d') ?? '∞' }} - {{ $item->end_at?->format('M d') ?? '∞' }}
                            @else
                                Always On
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="editAnnouncement({{ json_encode($item) }})" class="text-xs font-bold text-blue-400 hover:text-blue-300">Edit</button>
                                <form action="{{ route('admin.content.announcements.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this announcement?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-400 hover:text-red-300">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400">No announcements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-6 border-t border-white/10">
        {{ $announcements->links('vendor.pagination.admin') }}
    </div>
</div>

<!-- Modals -->
<dialog id="addAnnouncementModal" class="glass bg-slate-900 text-white p-0 rounded-3xl w-full max-w-md backdrop:bg-black/50">
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">New Announcement</h3>
            <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-white">✕</button>
        </div>
        <form action="{{ route('admin.content.announcements.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Title</label>
                <input type="text" name="title" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type</label>
                <select name="type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                    <option value="info">Info (Blue)</option>
                    <option value="warning">Warning (Amber)</option>
                    <option value="danger">Danger (Red)</option>
                    <option value="success">Success (Green)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Message</label>
                <textarea name="message" required rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Start Date</label>
                    <input type="date" name="start_at" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">End Date</label>
                    <input type="date" name="end_at" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                </div>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 font-bold transition-all mt-4">Create Announcement</button>
        </form>
    </div>
</dialog>

<dialog id="editAnnouncementModal" class="glass bg-slate-900 text-white p-0 rounded-3xl w-full max-w-md backdrop:bg-black/50">
    <div class="p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Edit Announcement</h3>
            <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-white">✕</button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Title</label>
                <input type="text" name="title" id="edit_title" required class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type</label>
                <select name="type" id="edit_type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                    <option value="info">Info (Blue)</option>
                    <option value="warning">Warning (Amber)</option>
                    <option value="danger">Danger (Red)</option>
                    <option value="success">Success (Green)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Message</label>
                <textarea name="message" id="edit_message" required rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Start Date</label>
                    <input type="date" name="start_at" id="edit_start_at" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">End Date</label>
                    <input type="date" name="end_at" id="edit_end_at" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-blue-500 outline-none">
                </div>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-500 font-bold transition-all mt-4">Update Announcement</button>
        </form>
    </div>
</dialog>

<script>
    function editAnnouncement(item) {
        const form = document.getElementById('editForm');
        form.action = `/admin/content/announcements/${item.id}`;
        
        document.getElementById('edit_title').value = item.title;
        document.getElementById('edit_type').value = item.type;
        document.getElementById('edit_message').value = item.message;
        document.getElementById('edit_start_at').value = item.start_at ? item.start_at.split('T')[0] : '';
        document.getElementById('edit_end_at').value = item.end_at ? item.end_at.split('T')[0] : '';
        
        document.getElementById('editAnnouncementModal').showModal();
    }
</script>
@endsection
