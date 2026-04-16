import React, { useEffect, useState } from 'react';
import { ShoppingBag, Search, Filter, ArrowRight, Home } from 'lucide-react';
import { Link } from 'react-router-dom';
import WebsiteLayout from '../layouts/WebsiteLayout';
import client from '../api/client';

interface Product {
  id: number;
  name: string;
  category: string;
  price?: number;
  image?: string;
  image_url?: string;
  description?: string;
}

const Shop: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('All');

  useEffect(() => {
    client.get('/public/products')
      .then(res => {
        if (res.data.success) {
          setProducts(res.data.data);
          setFilteredProducts(res.data.data);
        }
        setLoading(false);
      })
      .catch((err) => {
        console.error('Failed to fetch products:', err);
        setLoading(false);
      });
  }, []);

  useEffect(() => {
    let result = products;
    
    if (searchTerm) {
      result = result.filter(p => 
        p.name.toLowerCase().includes(searchTerm.toLowerCase()) || 
        p.category.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }
    
    if (selectedCategory !== 'All') {
      result = result.filter(p => p.category === selectedCategory);
    }
    
    setFilteredProducts(result);
  }, [searchTerm, selectedCategory, products]);

  const categories = ['All', ...Array.from(new Set(products.map(p => p.category)))];

  return (
    <WebsiteLayout darkMode={true}>
      {/* Shop Hero */}
      <section className="relative pt-40 pb-20 px-6 overflow-hidden">
        <div className="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/10 blur-[120px] rounded-full -mr-48 -mt-48 pointer-events-none"></div>
        
        <div className="max-w-7xl mx-auto relative z-10">
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
            <div className="space-y-4">
              <div className="flex items-center gap-2 text-indigo-500 font-bold text-xs uppercase tracking-widest">
                <Link to="/" className="hover:text-white transition-colors flex items-center gap-1">
                  <Home size={12} /> Home
                </Link>
                <span>/</span>
                <span>Marketplace</span>
              </div>
              <h1 className="text-5xl lg:text-7xl font-bold text-white tracking-tight">Our Shop.</h1>
            </div>
            <p className="max-w-md text-slate-400 font-medium leading-relaxed">
              Browse our complete catalog of digital infrastructure, data nodes, and premium services.
            </p>
          </div>

          {/* Filters & Search */}
          <div className="flex flex-col lg:flex-row gap-6 mb-12">
            <div className="relative flex-1 group">
              <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-indigo-500 transition-colors" size={20} />
              <input 
                type="text" 
                placeholder="Search products, services, or categories..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full bg-white/5 border border-white/5 p-6 pl-14 rounded-3xl outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-sm text-white placeholder:text-slate-700"
              />
            </div>
            <div className="flex gap-4 overflow-x-auto pb-2 lg:pb-0 scrollbar-hide">
              {categories.map(cat => (
                <button
                  key={cat}
                  onClick={() => setSelectedCategory(cat)}
                  className={`px-8 py-2 rounded-2xl text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-all border ${
                    selectedCategory === cat 
                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-lg shadow-indigo-600/20' 
                    : 'bg-white/5 text-slate-500 border-white/5 hover:bg-white/10 hover:text-white'
                  }`}
                >
                  {cat}
                </button>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Product Grid */}
      <section className="pb-40 px-6">
        <div className="max-w-7xl mx-auto">
          {loading ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
              {[1,2,3,4,5,6,7,8].map(i => (
                <div key={i} className="bg-white/5 p-8 rounded-[40px] border border-white/5 animate-pulse">
                  <div className="aspect-square bg-white/5 rounded-3xl mb-8"></div>
                  <div className="h-6 bg-white/5 rounded-lg w-3/4 mb-4"></div>
                  <div className="h-4 bg-white/5 rounded-lg w-1/2 mb-8"></div>
                  <div className="h-14 bg-white/5 rounded-2xl w-full"></div>
                </div>
              ))}
            </div>
          ) : filteredProducts.length > 0 ? (
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
              {filteredProducts.map((p) => (
                <div key={p.id} className="group bg-white/5 p-8 rounded-[40px] border border-white/5 hover:bg-white/[0.08] transition-all duration-500 flex flex-col">
                    <div className="aspect-square bg-slate-800 rounded-3xl mb-8 relative overflow-hidden">
                       {(p.image || p.image_url) ? (
                         <img src={p.image || p.image_url} alt={p.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                       ) : (
                         <div className="w-full h-full flex items-center justify-center text-slate-700">
                            <ShoppingBag size={48} />
                         </div>
                       )}
                       <div className="absolute top-4 right-4 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-wider border border-white/10">
                          {p.category}
                       </div>
                    </div>
                    <div className="flex-1">
                        <h3 className="text-xl font-bold text-white mb-2 leading-tight group-hover:text-indigo-300 transition-colors">{p.name}</h3>
                        <p className="text-slate-400 text-sm mb-6 line-clamp-2 font-medium">{p.description || 'Premium digital service optimized for performance and reliability.'}</p>
                    </div>
                    <div className="mt-auto">
                        <div className="flex items-center justify-between mb-6">
                             <p className="text-indigo-300 font-bold text-lg">₦{Number(p.price || 0).toLocaleString()}</p>
                             <span className="text-[10px] font-bold text-slate-600 uppercase tracking-widest">In Stock</span>
                        </div>
                        <button className="w-full py-4 bg-indigo-600 hover:bg-white hover:text-slate-950 text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all shadow-lg shadow-indigo-600/10">
                            Purchase Now
                        </button>
                    </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="text-center py-40 bg-white/5 rounded-[64px] border border-white/5">
                <div className="w-20 h-20 bg-white/5 rounded-3xl mx-auto flex items-center justify-center text-slate-700 mb-8">
                    <Filter size={32} />
                </div>
                <h3 className="text-3xl font-bold text-white mb-4 tracking-tight">No products found.</h3>
                <p className="text-slate-400 font-medium mb-10">We couldn't find any products matching your current filters.</p>
                <button 
                  onClick={() => {setSearchTerm(''); setSelectedCategory('All');}}
                  className="px-8 py-4 bg-white text-slate-950 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all"
                >
                    Clear All Filters
                </button>
            </div>
          )}
        </div>
      </section>

      {/* Support Banner */}
      <section className="py-20 px-6">
          <div className="max-w-7xl mx-auto">
               <div className="bg-indigo-600 rounded-[48px] p-12 lg:p-20 relative overflow-hidden group">
                   <div className="absolute top-0 right-0 w-96 h-96 bg-white/10 blur-[80px] rounded-full -mr-48 -mt-48 transition-transform group-hover:scale-150 duration-700"></div>
                   <div className="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                       <div className="space-y-4 text-center lg:text-left">
                           <h2 className="text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight">Can't find what <br /> you're looking for?</h2>
                           <p className="text-indigo-100/70 font-medium max-w-sm">Contact our specialized procurement team for custom digital infrastructure requests.</p>
                       </div>
                       <Link to="#" className="px-12 py-7 bg-white text-slate-900 rounded-[32px] font-bold text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-2xl flex items-center gap-3">
                           Speak with Support <ArrowRight size={18} />
                       </Link>
                   </div>
               </div>
          </div>
      </section>
    </WebsiteLayout>
  );
};

export default Shop;
