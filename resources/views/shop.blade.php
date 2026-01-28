@extends('layouts.app')

@section('title', 'Shop Premium Collection')

@section('content')
<div class="relative py-24 px-6 lg:px-8 bg-[#0f172a] min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-20 space-y-4">
            <span class="text-blue-400 font-bold uppercase tracking-[0.3em] text-[10px]">Shop All</span>
            <h1 class="text-5xl lg:text-7xl font-black text-white tracking-tighter">Premium Collection</h1>
            <p class="text-gray-500 max-w-xl mx-auto text-lg leading-relaxed">Browse our full catalog of high-end fashion and specialized essentials.</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
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
                        <p class="text-xs text-gray-400 line-clamp-2">{{ $product->description }}</p>
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
    </div>
</div>
@endsection
