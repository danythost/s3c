@extends('layouts.admin')

@section('title', 'Edit Page - ' . $page->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Edit Page: {{ $page->title }}</h1>
            <p class="text-gray-400">Update content for <code class="text-blue-400">{{ $page->slug }}</code> section.</p>
        </div>
        <a href="{{ route('admin.pages.index') }}" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-sm font-semibold transition-all">
            Back to Pages
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Slug -->
                <div class="glass p-6 rounded-2xl border-white/5 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-white/5 pb-4 mb-4">General Info</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Page Title</label>
                            <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                            @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Slug (Read Only)</label>
                            <input type="text" value="{{ $page->slug }}" readonly class="w-full bg-white/5 border border-white/5 rounded-xl px-4 py-3 text-gray-400 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Main Content</label>
                        <textarea name="content" rows="6" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">{{ old('content', $page->content) }}</textarea>
                        @error('content') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-2">This is the primary description text for the section.</p>
                    </div>
                </div>

                <!-- Structured Meta Fields (Alpine.js for JSON handling) -->
                <div class="glass p-6 rounded-2xl border-white/5 space-y-4" x-data="{ meta: {{ json_encode($page->meta ?? []) }} }">
                    <h3 class="text-lg font-bold text-white border-b border-white/5 pb-4 mb-4">Structured Data (Advanced)</h3>
                    
                    <!-- Dynamic Fields based on Slug -->
                    @if($page->slug === 'home')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Hero Prefix</label>
                                <input type="text" name="meta[hero_title_prefix]" x-model="meta.hero_title_prefix" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Hero Suffix (Gradient)</label>
                                <input type="text" name="meta[hero_title_suffix]" x-model="meta.hero_title_suffix" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Hero End</label>
                                <input type="text" name="meta[hero_title_end]" x-model="meta.hero_title_end" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                    @elseif($page->slug === 'about')
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Section Subtitle</label>
                                    <input type="text" name="meta[section_subtitle]" x-model="meta.section_subtitle" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Section Title</label>
                                    <input type="text" name="meta[section_title]" x-model="meta.section_title" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white">
                                </div>
                            </div>
                            <!-- Steps Editor could be added here, but keep simple for now -->
                             <p class="text-xs text-yellow-500">Note: 'Steps' structure is currently managed via database/seeder. Use JSON editor if advanced changes needed.</p>
                        </div>
                    @elseif($page->slug === 'developers')
                         <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Section Subtitle</label>
                                    <input type="text" name="meta[section_subtitle]" x-model="meta.section_subtitle" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Section Title</label>
                                    <input type="text" name="meta[section_title]" x-model="meta.section_title" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white">
                                </div>
                            </div>
                        </div>
                    @elseif($page->slug === 'ceo')
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">CEO Name</label>
                                    <input type="text" name="meta[name]" x-model="meta.name" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Position / Title</label>
                                    <input type="text" name="meta[position]" x-model="meta.position" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Signature / Quote</label>
                                    <input type="text" name="meta[signature_text]" x-model="meta.signature_text" class="w-full bg-[#0f172a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:col-span-2 mt-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">WhatsApp</label>
                                        <input type="text" name="meta[socials][whatsapp]" x-model="meta.socials.whatsapp" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Facebook</label>
                                        <input type="text" name="meta[socials][facebook]" x-model="meta.socials.facebook" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Gmail</label>
                                        <input type="text" name="meta[socials][gmail]" x-model="meta.socials.gmail" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Telegram</label>
                                        <input type="text" name="meta[socials][telegram]" x-model="meta.socials.telegram" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-xs">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($page->slug === 'team')
                        <div class="space-y-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-bold uppercase text-gray-500">Team Members</label>
                                <button type="button" @click="meta.members = [...(meta.members || []), {name: '', role: '', facebook: '', whatsapp: '', linkedin: ''}]" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 rounded-lg text-xs font-bold uppercase transition-all">+ Add Member</button>
                            </div>
                            <template x-for="(member, index) in meta.members" :key="index">
                                <div class="glass p-4 rounded-xl border-white/5 space-y-4 relative group">
                                    <button type="button" @click="meta.members = meta.members.filter((_, i) => i !== index)" class="absolute top-2 right-2 text-red-400 hover:text-red-300 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <input type="text" :name="`meta[members][${index}][name]`" x-model="member.name" placeholder="Name" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                                        <input type="text" :name="`meta[members][${index}][role]`" x-model="member.role" placeholder="Role" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-sm">
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <input type="text" :name="`meta[members][${index}][facebook]`" x-model="member.facebook" placeholder="Facebook Link" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-[10px]">
                                        <input type="text" :name="`meta[members][${index}][whatsapp]`" x-model="member.whatsapp" placeholder="WhatsApp Number/Link" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-[10px]">
                                        <input type="text" :name="`meta[members][${index}][linkedin]`" x-model="member.linkedin" placeholder="LinkedIn Link" class="w-full bg-[#0f172a] border border-white/10 rounded-lg px-3 py-2 text-white text-[10px]">
                                    </div>
                                </div>
                            </template>
                             <div x-show="!meta.members || meta.members.length === 0" class="text-center py-4 text-gray-500 text-sm italic">
                                No team members added yet.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status -->
                <div class="glass p-6 rounded-2xl border-white/5 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-white/5 pb-4 mb-4">Publish</h3>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-600 bg-[#0f172a] text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-900">
                        <span class="text-gray-300 font-medium select-none">Active</span>
                    </label>
                    
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-lg shadow-blue-500/20">
                        Save Changes
                    </button>
                </div>

                <!-- Image -->
                <div class="glass p-6 rounded-2xl border-white/5 space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-white/5 pb-4 mb-4">Featured Image</h3>
                    
                    @if($page->image_url)
                        <div class="rounded-xl overflow-hidden border border-white/10 mb-4">
                            <img src="{{ asset($page->image_url) }}" alt="Preview" class="w-full h-auto">
                        </div>
                    @endif

                    <!-- Hidden File Input with Custom Button -->
                    <div x-data="{ fileName: '' }">
                        <label class="block w-full cursor-pointer group">
                             <div class="border-2 border-dashed border-white/10 rounded-xl p-6 text-center hover:border-blue-500/50 hover:bg-blue-500/5 transition-all">
                                <svg class="w-8 h-8 mx-auto text-gray-500 group-hover:text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-sm text-gray-400 group-hover:text-white" x-text="fileName || 'Click to Upload New Image'"></span>
                             </div>
                             <input type="file" name="image" class="hidden" accept="image/*" @change="fileName = $event.target.files[0].name">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
