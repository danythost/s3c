@extends('layouts.app')

@section('title', 'Fast & Reliable Data Top-Up, Anytime')

@section('content')
<div class="relative min-h-[80vh] flex items-center justify-center px-6 lg:px-8 overflow-hidden rounded-[3rem] mt-6 mx-4 lg:mx-8">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero_bg.png') }}" alt="S3C Hero Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0f172a]/95 via-[#0f172a]/80 to-transparent"></div>
    </div>

    <!-- Hero Section Content -->
    <div class="relative z-10 max-w-4xl mx-auto text-center py-20">
        <h1 class="text-5xl lg:text-7xl font-extrabold mb-8 tracking-tight">
            {{ $pages['home']->meta['hero_title_prefix'] ?? 'Fast & Reliable' }} 
            <span class="bg-gradient-to-r from-blue-400 via-indigo-400 to-emerald-400 bg-clip-text text-transparent">
                {{ $pages['home']->meta['hero_title_suffix'] ?? 'Solutions' }}
            </span> 
            {{ $pages['home']->meta['hero_title_end'] ?? 'for Your Lifestyle' }}
        </h1>
        <p class="text-xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto drop-shadow-lg">
            {{ $pages['home']->content }}
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-500/20 transition-all transform hover:scale-105">
                    Open Dashboard
                </a>
            @else
                <a href="{{ route('vtu.data.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-500/20 transition-all transform hover:scale-105">
                    Buy Data Now
                </a>
                <a href="{{ route('shop') }}" class="glass hover:bg-white/10 text-white px-10 py-4 rounded-2xl font-bold text-lg transition-all">
                    Shop Here
                </a>
            @endauth
        </div>

        <!-- Trust Signals -->
        <div class="flex flex-wrap justify-center gap-8 text-sm font-medium text-gray-300">
            <div class="flex items-center gap-2">
                <span class="text-emerald-400">✓</span> Instant Delivery
            </div>
            <div class="flex items-center gap-2">
                <span class="text-emerald-400">✓</span> Premium Products
            </div>
            <div class="flex items-center gap-2">
                <span class="text-emerald-400">✓</span> Secure Payments
            </div>
        </div>
    </div>
</div>

<!-- Main Body Sections -->
<div class="relative py-24 px-6 lg:px-8">
    <!-- Services Overview -->

    <!-- Products Showcase (2x4 Grid) -->
    <div class="max-w-7xl mx-auto mb-40 px-4">
        <div class="text-center mb-20 space-y-4">
            <span class="text-blue-400 font-bold uppercase tracking-[0.3em] text-[10px]">Curated Marketplace</span>
            <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tighter">Premium Lifestyle Collection</h2>
            <p class="text-gray-500 max-w-xl mx-auto text-lg leading-relaxed">Discover our exclusive selection of high-end fashion and specialized essentials, tailored for the modern individual.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @forelse($products as $product)
                <div class="glass group rounded-[2.5rem] overflow-hidden hover:bg-white/10 transition-all duration-500 border border-white/5 hover:border-blue-500/30 relative">
                    <div class="aspect-square overflow-hidden relative">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 bg-[#1a1a2e] group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-[#1a1a2e] flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4">
                            <span class="text-[9px] uppercase tracking-widest text-blue-400 font-black bg-[#0f172a]/80 backdrop-blur-md px-3 py-1.5 rounded-full border border-blue-500/20">{{ $product->category ?: 'Product' }}</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <h3 class="text-sm font-black text-white truncate uppercase tracking-tight">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-[10px] text-gray-500 font-bold uppercase">Price</p>
                                <span class="text-white font-black text-md">₦{{ number_format($product->price, 2) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="https://wa.me/234XXXXXXXXXX?text=I'm%20interested%20in%20your%20product:%20{{ urlencode($product->name) }}" target="_blank" class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all transform group-hover:scale-110" title="Order on WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                </a>
                                <a href="https://facebook.com/YOUR_PAGE" target="_blank" class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all transform group-hover:scale-110" title="Follow on Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-500 font-bold italic">New premium collection arriving soon. Stay tuned!</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-20">
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-3 bg-white text-gray-900 px-12 py-5 rounded-[2rem] font-black text-lg shadow-2xl hover:bg-blue-50 shadow-blue-500/20 transition-all transform hover:scale-105 active:scale-95">
                Explore Full Catalog
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>

        <!-- How It Works (Dynamic) -->
    @if(isset($pages['about']))
    <div id="about" class="max-w-7xl mx-auto mb-40">
        <div class="text-center mb-24 space-y-4">
            <span class="text-emerald-400 font-bold uppercase tracking-[0.3em] text-[10px]">{{ $pages['about']->meta['section_subtitle'] ?? 'Seamless Experience' }}</span>
            <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tighter">{{ $pages['about']->title }}</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg">{{ $pages['about']->content }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @foreach($pages['about']->meta['steps'] ?? [] as $index => $step)
                <div class="glass p-10 rounded-[3rem] border-white/5 relative overflow-hidden group hover:bg-white/10 transition-all duration-500">
                    <div class="absolute -top-10 -left-10 text-[12rem] font-black text-white/5 opacity-40 group-hover:text-{{ $step['color'] ?? 'blue' }}-500/10 transition-colors pointer-events-none">{{ $index + 1 }}</div>
                    <div class="relative">
                        <div class="text-3xl mb-8 bg-{{ $step['color'] ?? 'blue' }}-500/10 w-20 h-20 flex items-center justify-center rounded-[2rem] text-{{ $step['color'] ?? 'blue' }}-400 font-black border border-{{ $step['color'] ?? 'blue' }}-500/20">0{{ $index + 1 }}</div>
                        <h3 class="text-2xl font-black mb-4 text-white">{{ $step['title'] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed font-bold">{{ $step['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- CEO Section -->
        @if(isset($pages['ceo']))
        <div class="mt-40 max-w-4xl mx-auto">
            <div class="glass p-10 rounded-[3rem] border-white/5 relative overflow-hidden flex flex-col md:flex-row items-center gap-10">
                <!-- CEO Image -->
                <div class="shrink-0 relative group">
                    <div class="w-48 h-48 md:w-56 md:h-56 aspect-square rounded-full overflow-hidden border-4 border-blue-500/20 relative z-10">
                        @if($pages['ceo']->image_url)
                            <img src="{{ asset($pages['ceo']->image_url) }}" alt="{{ $pages['ceo']->meta['name'] ?? 'CEO' }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-500/10 flex items-center justify-center text-blue-400">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        @endif
                    </div>
                     <div class="absolute inset-0 bg-blue-500/20 blur-3xl rounded-full opacity-0 group-hover:opacity-50 transition-opacity duration-500"></div>
                </div>
                
                <!-- CEO Content -->
                <div class="text-center md:text-left">
                    <div class="inline-block px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold uppercase tracking-widest mb-4">
                        {{ $pages['ceo']->meta['position'] ?? 'Founder & CEO' }}
                    </div>
                    <h3 class="text-3xl font-black text-white mb-4">{{ $pages['ceo']->meta['name'] ?? 'CEO Name' }}</h3>
                    <p class="text-gray-400 text-lg leading-relaxed mb-6 font-medium italic">
                        "{{ $pages['ceo']->content }}"
                    </p>
                    <p class="text-blue-400 font-handwriting text-xl opacity-80 mb-6">
                        {{ $pages['ceo']->meta['signature_text'] ?? '' }}
                    </p>

                    <div class="flex items-center justify-center md:justify-start gap-4">
                        @if(!empty($pages['ceo']->meta['socials']['whatsapp']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pages['ceo']->meta['socials']['whatsapp']) }}" target="_blank" class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400 hover:bg-emerald-500 hover:text-white transition-all shadow-lg shadow-emerald-500/10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .004 5.412 0 12.048c0 2.123.554 4.197 1.606 6.023L0 24l6.137-1.61a11.846 11.846 0 005.9 1.538h.005c6.634 0 12.045-5.413 12.048-12.05a11.79 11.79 0 00-3.517-8.417"/></svg>
                            </a>
                        @endif
                        @if(!empty($pages['ceo']->meta['socials']['facebook']))
                            <a href="{{ $pages['ceo']->meta['socials']['facebook'] }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600/10 flex items-center justify-center text-blue-400 hover:bg-blue-600 hover:text-white transition-all shadow-lg shadow-blue-500/10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if(!empty($pages['ceo']->meta['socials']['gmail']))
                            <a href="mailto:{{ $pages['ceo']->meta['socials']['gmail'] }}" class="w-10 h-10 rounded-full bg-red-600/10 flex items-center justify-center text-red-500 hover:bg-red-600 hover:text-white transition-all shadow-lg shadow-red-500/10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                        @endif
                        @if(!empty($pages['ceo']->meta['socials']['telegram']))
                            <a href="{{ $pages['ceo']->meta['socials']['telegram'] }}" target="_blank" class="w-10 h-10 rounded-full bg-sky-500/10 flex items-center justify-center text-sky-400 hover:bg-sky-500 hover:text-white transition-all shadow-lg shadow-sky-500/10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zM17.8 7.3a.6.6 0 0 1 .2.6c0 .2 0 .4-.2.5l-2.6 11.2c-.1.5-.4.6-.8.4l-4.1-3.1-2 1.9c-.2.2-.4.4-.6.4l.3-4.2 7.7-7c.3-.3 0-.4-.4-.2l-9.5 6-4.1-1.3c-.9-.3-.9-.9.2-1.3L17.2 7.2c.4-.2.6-.2.6.1z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Developers Section (Dynamic) -->
    @if(isset($pages['developers']))
    <div id="developers" class="max-w-7xl mx-auto mb-40">
        <div class="glass p-12 lg:p-20 rounded-[3rem] border-indigo-500/20 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/20 to-transparent"></div>
            <div class="relative grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-indigo-400 font-bold uppercase tracking-[0.3em] text-[10px] mb-4 block">{{ $pages['developers']->meta['section_subtitle'] ?? 'For Developers' }}</span>
                    <h2 class="text-4xl lg:text-5xl font-black text-white mb-6 tracking-tighter">{{ $pages['developers']->title }}</h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-8">{{ $pages['developers']->content }}</p>
                    
                    <ul class="space-y-3 mb-8">
                         @foreach($pages['developers']->meta['features'] ?? [] as $feature)
                            <li class="flex items-center gap-3 text-gray-300 font-bold">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                {{ $feature }}
                            </li>
                         @endforeach
                    </ul>

                    <a href="{{ $pages['developers']->meta['docs_link'] ?? '#' }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-xl font-bold inline-flex items-center gap-2 transition-all">
                        Read Documentation
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <!-- Code Snippet Visual -->
                <div class="bg-[#0f172a] rounded-2xl p-6 border border-white/10 shadow-2xl relative group">
                    <div class="flex gap-2 mb-4">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    </div>
                    <pre class="font-mono text-xs text-blue-300 overflow-x-auto"><code><span class="text-purple-400">POST</span> /api/v1/purchase/data
Authorization: Bearer <span class="text-yellow-400">sk_live_...</span>
Content-Type: application/json

{
  <span class="text-emerald-400">"network"</span>: <span class="text-orange-400">"MTN"</span>,
  <span class="text-emerald-400">"phone"</span>: <span class="text-orange-400">"08012345678"</span>,
  <span class="text-emerald-400">"plan"</span>: <span class="text-orange-400">"1GB"</span>
}</code></pre>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Team Section (Dynamic) -->
    @if(isset($pages['team']))
    <div id="team" class="max-w-7xl mx-auto mb-40 px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-5xl font-black text-white tracking-tighter mb-4">{{ $pages['team']->title }}</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg">{{ $pages['team']->content }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($pages['team']->meta['members'] ?? [] as $member)
                @if(!empty($member['name']))
                    <div class="glass p-8 rounded-[2.5rem] border-white/5 relative group hover:bg-white/10 transition-all duration-500">
                    <div class="relative z-10">
                        <div class="mb-6">
                            <h3 class="text-2xl font-black text-white mb-1">{{ $member['name'] }}</h3>
                            <p class="text-blue-400 font-bold text-sm uppercase tracking-widest">{{ $member['role'] }}</p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            @if(!empty($member['facebook']))
                                <a href="{{ $member['facebook'] }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-600/10 flex items-center justify-center text-blue-400 hover:bg-blue-600 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            @if(!empty($member['whatsapp']))
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $member['whatsapp']) }}" target="_blank" class="w-10 h-10 rounded-full bg-emerald-600/10 flex items-center justify-center text-emerald-400 hover:bg-emerald-600 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .004 5.412 0 12.048c0 2.123.554 4.197 1.606 6.023L0 24l6.137-1.61a11.846 11.846 0 005.9 1.538h.005c6.634 0 12.045-5.413 12.048-12.05a11.79 11.79 0 00-3.517-8.417"/></svg>
                                </a>
                            @endif
                            @if(!empty($member['linkedin']))
                                <a href="{{ $member['linkedin'] }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-300 hover:bg-blue-500 hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.454C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Supported Networks -->
    <div class="max-w-7xl mx-auto mb-32 text-center">
        <h2 class="text-2xl font-bold text-white uppercase tracking-widest mb-12">Supported Networks</h2>
        <div class="flex flex-wrap justify-center items-center gap-12 opacity-40 hover:opacity-100 transition-opacity">
            @foreach(['MTN', 'Airtel', 'Glo', '9mobile'] as $network)
                <span class="text-3xl font-black tracking-tighter text-white">{{ $network }}</span>
            @endforeach
        </div>
    </div>

    <!-- Final CTA -->
    <div class="max-w-5xl mx-auto mb-32">
        <div class="glass p-12 lg:p-20 rounded-[3rem] text-center relative overflow-hidden border-indigo-500/20">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/10 to-transparent"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6 text-white">Get Started with S3C</h2>
                <p class="text-lg text-gray-400 mb-10 leading-relaxed">
                    Join thousands of users enjoying fast data purchases and premium lifestyle shopping.
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-900 px-10 py-4 rounded-2xl font-bold text-lg shadow-xl hover:bg-gray-100 transition-all transform hover:scale-105">
                    Create Account
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto border-t border-white/10 pt-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
            <div class="col-span-2">
                <a href="/" class="text-3xl font-bold bg-gradient-to-r from-blue-400 to-emerald-400 bg-clip-text text-transparent mb-6 inline-block">S3C</a>
                <p class="text-white/70 max-w-xs text-sm font-medium">Professional VTU & E-commerce platform for fast data top-up and premium lifestyle products.</p>
            </div>
            <div id="contact">
                <h4 class="font-bold mb-4 text-white uppercase tracking-widest text-xs">Company</h4>
                <ul class="space-y-2 text-sm text-white/60">
                    <li><a href="#about" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="mailto:support@s3c.com.ng" class="hover:text-white transition-colors">Contact Support</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 text-white uppercase tracking-widest text-xs">Legal</h4>
                <ul class="space-y-2 text-sm text-white/60">
                    <li><a href="#" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-[10px] text-white/30 uppercase tracking-[0.3em] font-bold pb-12">
            © {{ date('Y') }} S3C. All rights reserved.
        </div>
    </footer>
</div>
@endsection

