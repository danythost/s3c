@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-3xl font-bold text-white mb-2">Products</h1>
        <p class="text-gray-400 text-sm">Manage your store products and availability.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
        Add Product
    </a>
</div>

<div class="glass rounded-3xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10">
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Product</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Category</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Price</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                    <th class="p-4 text-xs font-bold text-gray-400 uppercase text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($products as $product)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center overflow-hidden">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400">{{ Str::limit($product->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-sm text-gray-300">{{ $product->category ?: 'Uncategorized' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="text-sm font-bold text-white">₦{{ number_format($product->price, 2) }}</span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $product->status == 'active' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $product->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 hover:bg-white/10 rounded-lg text-blue-400 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-white/10 rounded-lg text-red-400 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400">
                            No products found. <a href="{{ route('admin.products.create') }}" class="text-blue-400 underline">Add your first product</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="p-4 border-t border-white/10">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
