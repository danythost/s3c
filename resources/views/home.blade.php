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
            Fast & Reliable <span class="bg-gradient-to-r from-blue-400 via-indigo-400 to-emerald-400 bg-clip-text text-transparent">Solutions</span> for Your Lifestyle
        </h1>
        <p class="text-xl text-gray-300 mb-12 leading-relaxed max-w-2xl mx-auto drop-shadow-lg">
            Instant mobile data top-up and a premium marketplace for quality fashion, clothes, bags, and shoes. Everything you need, in one secure place.
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
                <a href="{{ route('register') }}" class="glass hover:bg-white/10 text-white px-10 py-4 rounded-2xl font-bold text-lg transition-all">
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
            @php
                $products = [
                    ['name' => 'Oxford Leather Shoes', 'img' => 'shoes.png', 'price' => '₦45,000', 'tag' => 'Footwear'],
                    ['name' => 'Designer Handbag', 'img' => 'bag.png', 'price' => '₦32,500', 'tag' => 'Accessories'],
                    ['name' => 'Luxury Business Suit', 'img' => 'suit.png', 'price' => '₦120,000', 'tag' => 'Clothing'],
                    ['name' => 'Streetwear Snapback', 'img' => 'cap.png', 'price' => '₦8,500', 'tag' => 'Headwear'],
                    ['name' => 'Swiss Chronograph', 'img' => 'watch.png', 'price' => '₦85,000', 'tag' => 'Luxury'],
                    ['name' => 'Premium Wallet', 'img' => 'bag.png', 'price' => '₦15,000', 'tag' => 'Accessories'],
                    ['name' => 'Designer Belt', 'img' => 'suit.png', 'price' => '₦12,000', 'tag' => 'Accessories'],
                    ['name' => 'Minimalist Sneakers', 'img' => 'shoes.png', 'price' => '₦28,000', 'tag' => 'Footwear'],
                ];
            @endphp

            @foreach($products as $product)
                <div class="glass group rounded-[2.5rem] overflow-hidden hover:bg-white/10 transition-all duration-500 border border-white/5 hover:border-blue-500/30 relative">
                    <div class="aspect-square overflow-hidden relative">
                        <img src="{{ asset('images/products/' . $product['img']) }}" alt="{{ $product['name'] }}" 
                             class="w-full h-full object-cover transition-transform duration-700 bg-[#1a1a2e] group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent opacity-60"></div>
                        <div class="absolute top-4 left-4">
                            <span class="text-[9px] uppercase tracking-widest text-blue-400 font-black bg-[#0f172a]/80 backdrop-blur-md px-3 py-1.5 rounded-full border border-blue-500/20">{{ $product['tag'] }}</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <h3 class="text-sm font-black text-white truncate uppercase tracking-tight">{{ $product['name'] }}</h3>
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-[10px] text-gray-500 font-bold uppercase">Price</p>
                                <span class="text-white font-black text-md">{{ $product['price'] }}</span>
                            </div>
                            <button class="w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all transform group-hover:scale-110">
                                <span class="text-xl font-bold">+</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-20">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-3 bg-white text-gray-900 px-12 py-5 rounded-[2rem] font-black text-lg shadow-2xl hover:bg-blue-50 shadow-blue-500/20 transition-all transform hover:scale-105 active:scale-95">
                Explore Full Catalog
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>

    <!-- How It Works -->
    <div id="about" class="max-w-7xl mx-auto mb-40">
        <div class="text-center mb-24 space-y-4">
            <span class="text-emerald-400 font-bold uppercase tracking-[0.3em] text-[10px]">Seamless Experience</span>
            <h2 class="text-4xl lg:text-5xl font-black text-white tracking-tighter">How S3C Simplifies Life</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="glass p-10 rounded-[3rem] border-white/5 relative overflow-hidden group hover:bg-white/10 transition-all duration-500">
                <div class="absolute -top-10 -left-10 text-[12rem] font-black text-white/5 opacity-40 group-hover:text-blue-500/10 transition-colors pointer-events-none">1</div>
                <div class="relative">
                    <div class="text-3xl mb-8 bg-blue-500/10 w-20 h-20 flex items-center justify-center rounded-[2rem] text-blue-400 font-black border border-blue-500/20">01</div>
                    <h3 class="text-2xl font-black mb-4 text-white">Create Profile</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-bold">Secure registration in seconds. Your automated digital wallet is instantly provisioned upon signup.</p>
                </div>
            </div>
            <div class="glass p-10 rounded-[3rem] border-white/5 relative overflow-hidden group hover:bg-white/10 transition-all duration-500">
                <div class="absolute -top-10 -left-10 text-[12rem] font-black text-white/5 opacity-40 group-hover:text-emerald-500/10 transition-colors pointer-events-none">2</div>
                <div class="relative">
                    <div class="text-3xl mb-8 bg-emerald-500/10 w-20 h-20 flex items-center justify-center rounded-[2rem] text-emerald-400 font-black border border-emerald-500/20">02</div>
                    <h3 class="text-2xl font-black mb-4 text-white">Smart Funding</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-bold">Use your dedicated virtual bank account for real-time funding. Transact with confidence and total transparency.</p>
                </div>
            </div>
            <div class="glass p-10 rounded-[3rem] border-white/5 relative overflow-hidden group hover:bg-white/10 transition-all duration-500">
                <div class="absolute -top-10 -left-10 text-[12rem] font-black text-white/5 opacity-40 group-hover:text-purple-500/10 transition-colors pointer-events-none">3</div>
                <div class="relative">
                    <div class="text-3xl mb-8 bg-purple-500/10 w-20 h-20 flex items-center justify-center rounded-[2rem] text-purple-400 font-black border border-purple-500/20">03</div>
                    <h3 class="text-2xl font-black mb-4 text-white">Shop & Deliver</h3>
                    <p class="text-gray-500 text-sm leading-relaxed font-bold">Instantly top-up data or explore our premium marketplace. Experience lightning-fast delivery on all services.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Supported Networks -->
    <div class="max-w-7xl mx-auto mb-32 text-center">
        <h2 class="text-2xl font-bold text-gray-500 uppercase tracking-widest mb-12">Supported Networks</h2>
        <div class="flex flex-wrap justify-center items-center gap-12 opacity-30 hover:opacity-100 transition-opacity">
            @foreach(['MTN', 'Airtel', 'Glo', '9mobile'] as $network)
                <span class="text-3xl font-black tracking-tighter">{{ $network }}</span>
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
                <p class="text-gray-500 max-w-xs text-sm">Professional VTU & E-commerce platform for fast data top-up and premium lifestyle products.</p>
            </div>
            <div id="contact">
                <h4 class="font-bold mb-4 text-white">Company</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#about" class="hover:text-blue-400 transition-colors">About Us</a></li>
                    <li><a href="mailto:support@s3c.com.ng" class="hover:text-blue-400 transition-colors">Contact Support</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-4 text-white">Legal</h4>
                <ul class="space-y-2 text-sm text-gray-500">
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Terms & Conditions</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center text-xs text-gray-600 pb-12">
            © {{ date('Y') }} S3C. All rights reserved.
        </div>
    </footer>
</div>
@endsection

