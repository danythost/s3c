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
    <div class="max-w-7xl mx-auto mb-32 px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold text-white tracking-tight">Premium Shop Collection</h2>
            <p class="text-gray-400 mt-4">Discover our curated selection of high-end fashion and lifestyle essentials.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
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
                <div class="glass group rounded-2xl overflow-hidden hover:bg-white/5 transition-all duration-300 border border-white/5 hover:border-blue-500/30">
                    <div class="aspect-square overflow-hidden relative">
                        <img src="{{ asset('images/products/' . $product['img']) }}" alt="{{ $product['name'] }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                            <span class="text-[10px] uppercase tracking-widest text-blue-400 font-bold bg-blue-400/10 px-2 py-1 rounded">{{ $product['tag'] }}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-bold text-white mb-1 truncate">{{ $product['name'] }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-blue-400 font-black text-sm">{{ $product['price'] }}</span>
                            <button class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all">
                                <span class="text-lg">+</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-16">
            <a href="{{ route('register') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-12 py-4 rounded-2xl font-bold text-lg shadow-xl shadow-blue-500/20 transition-all transform hover:scale-105">
                Explore Entire Shop
            </a>
        </div>
    </div>

    <!-- How It Works -->
    <div id="about" class="max-w-7xl mx-auto mb-32">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold">How It Works</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="glass p-8 rounded-3xl text-center relative overflow-hidden group">
                <div class="absolute -top-4 -left-4 text-8xl font-black text-white/5 group-hover:text-blue-500/10 transition-colors">1</div>
                <div class="relative">
                    <div class="text-3xl mb-6 bg-blue-500/20 w-16 h-16 flex items-center justify-center rounded-2xl mx-auto text-blue-400 font-bold">01</div>
                    <h3 class="text-xl font-bold mb-4">Create an Account</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Sign up in minutes and get a secure wallet automatically.</p>
                </div>
            </div>
            <div class="glass p-8 rounded-3xl text-center relative overflow-hidden group">
                <div class="absolute -top-4 -left-4 text-8xl font-black text-white/5 group-hover:text-emerald-500/10 transition-colors">2</div>
                <div class="relative">
                    <div class="text-3xl mb-6 bg-emerald-500/20 w-16 h-16 flex items-center justify-center rounded-2xl mx-auto text-emerald-400 font-bold">02</div>
                    <h3 class="text-xl font-bold mb-4">Fund Your Wallet</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Add funds to your wallet and manage your balance with full transparency.</p>
                </div>
            </div>
            <div class="glass p-8 rounded-3xl text-center relative overflow-hidden group">
                <div class="absolute -top-4 -left-4 text-8xl font-black text-white/5 group-hover:text-purple-500/10 transition-colors">3</div>
                <div class="relative">
                    <div class="text-3xl mb-6 bg-purple-500/20 w-16 h-16 flex items-center justify-center rounded-2xl mx-auto text-purple-400 font-bold">03</div>
                    <h3 class="text-xl font-bold mb-4">Shop & Top-Up</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Select your data plan or choose from our fashion items. Secure checkout instantly.</p>
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

