@extends('layouts.admin')

@section('title', 'Manage Pages')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Manage Pages</h1>
            <p class="text-gray-400">Edit content for Home, About, and other sections.</p>
        </div>
    </div>

    <!-- Pages List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pages as $page)
            <div class="glass p-6 rounded-2xl border-white/5 relative overflow-hidden group hover:bg-white/5 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 rounded-xl bg-blue-500/10 text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $page->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-gray-500/10 text-gray-400' }}">
                        {{ $page->is_active ? 'Active' : 'Draft' }}
                    </span>
                </div>
                
                <h3 class="text-lg font-bold text-white mb-1">{{ $page->title }}</h3>
                <code class="text-xs text-blue-300 bg-blue-500/10 px-2 py-0.5 rounded mb-4 inline-block">{{ $page->slug }}</code>
                
                <p class="text-sm text-gray-500 mb-6 line-clamp-2">{{ $page->content }}</p>

                <div class="flex items-center justify-between mt-auto">
                    <div class="text-xs text-gray-600">
                        Last updated: {{ $page->updated_at->diffForHumans() }}
                    </div>
                    <a href="{{ route('admin.pages.edit', $page) }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-sm font-semibold transition-all group-hover:bg-blue-600">
                        <span>Edit Content</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
