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
                            <button class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center hover:bg-blue-50 hover:text-white transition-all transform group-hover:scale-110">
                                <span class="text-xl font-bold">+</span>
                            </button>
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
