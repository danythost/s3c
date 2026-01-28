@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="mb-10">
    <a href="{{ route('admin.products.index') }}" class="text-blue-400 hover:text-blue-300 flex items-center gap-2 mb-4 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Products
    </a>
    <h1 class="text-3xl font-bold text-white">Edit Product: {{ $product->name }}</h1>
</div>

<div class="glass rounded-3xl p-8 max-w-4xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="text-sm font-bold text-gray-400">Product Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-all">
                @error('name') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="category" class="text-sm font-bold text-gray-400">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $product->category) }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-all"
                       placeholder="e.g. Electronics, Clothing">
                @error('category') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="price" class="text-sm font-bold text-gray-400">Price (₦)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-all">
                @error('price') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="status" class="text-sm font-bold text-gray-400">Status</label>
                <select id="status" name="status" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-all appearance-none">
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }} class="bg-[#0f172a]">Active</option>
                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }} class="bg-[#0f172a]">Inactive</option>
                </select>
                @error('status') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label for="image" class="text-sm font-bold text-gray-400">Product Image</label>
            @if($product->image_url)
                <div class="mb-2">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-xl border border-white/10">
                    <p class="text-xs text-gray-500 mt-1">Current Image</p>
                </div>
            @endif
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-all">
            @error('image') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="description" class="text-sm font-bold text-gray-400">Description</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-all">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 flex items-center justify-between">
            <button type="submit" class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/20">
                Update Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-white transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
