import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import WebsiteLayout from '../layouts/WebsiteLayout';
import client from '../api/client';
import { 
  ArrowRight, 
  Database, 
  Smartphone, 
  Zap, 
  Globe, 
  Shield, 
  ShoppingBag,
  ZapIcon,
  Users,
  Code2,
  PhoneCall,
  ChevronRight,
  TrendingUp,
  Cpu
} from 'lucide-react';

  interface Product {
    id: number;
    name: string;
    category: string;
    price?: number;
    image?: string;
    image_url?: string;
    description?: string;
  }
  
  interface PageData {
    title: string;
    content: string;
    image_url?: string;
    meta?: any;
  }
  
  const LandingPage: React.FC = () => {
    const { user } = useAuth();
    const [products, setProducts] = useState<Product[]>([]);
    const [pageData, setPageData] = useState<PageData | null>(null);
    const [ceoData, setCeoData] = useState<PageData | null>(null);
  
    // Fallback products if API is empty
    const defaultProducts = [
      { id: 1, name: 'Premium Data Node', category: 'Infrastructure', price: 5000 },
      { id: 2, name: 'Business Enterprise Hub', category: 'E-commerce', price: 15000 },
      { id: 3, name: 'Secure API Gateway', category: 'Developers', price: 25000 },
      { id: 4, name: 'Universal VTU License', category: 'Trading', price: 10000 },
    ];
  
    useEffect(() => {
      // Fetch products
      client.get('/public/products')
        .then(res => {
          console.log('API Products:', res.data);
          if (res.data.success && res.data.data.length > 0) {
            setProducts(res.data.data);
          } else {
            setProducts(defaultProducts);
          }
        })
        .catch((err) => {
          console.error('API Error:', err);
          setProducts(defaultProducts);
        });

      // Fetch home page data
      client.get('/public/pages/home')
        .then(res => {
          if (res.data && res.data.success && res.data.data) {
            setPageData(res.data.data);
          }
        })
        .catch(err => {
          console.error('API Error fetching page data:', err);
        });

      // Fetch CEO data
      client.get('/public/pages/ceo')
        .then(res => {
          if (res.data && res.data.success && res.data.data) {
            setCeoData(res.data.data);
          }
        })
        .catch(err => {
          console.error('API Error fetching ceo data:', err);
        });
    }, []);

  return (
    <WebsiteLayout darkMode={true}>
      {/* Hero Section */}
      <section className="relative pt-40 pb-52 px-6 overflow-hidden">
        {/* Background Decorations */}
        <div className="absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-600/10 blur-[120px] rounded-full -mr-96 -mt-96 pointer-events-none animate-pulse"></div>
        <div className="absolute bottom-0 left-0 w-[600px] h-[600px] bg-emerald-600/5 blur-[120px] rounded-full -ml-48 -mb-48 pointer-events-none"></div>

        <div className="max-w-7xl mx-auto relative z-10 flex flex-col items-center text-center">
          <div className="inline-flex items-center gap-3 bg-white/5 border border-white/10 px-6 py-2 rounded-full mb-10 shadow-premium animate-in slide-in-from-top duration-700">
            <span className="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
            <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Fast & Reliable Digital Services</span>
          </div>
          
          <h1 className="text-5xl lg:text-8xl font-bold text-white tracking-tight leading-[1.05] mb-10">
            {pageData?.title ? (
                <span>{pageData.title}</span>
            ) : (
                <>Your Daily <span className="text-indigo-500">Digital</span> <br /> Companion.</>
            )}
          </h1>
          
          <p className="max-w-2xl mx-auto text-slate-300 text-lg lg:text-xl font-medium leading-relaxed mb-14 opacity-90 whitespace-pre-line">
            {pageData?.content 
               ? pageData.content 
               : "Get instant data, airtime, and pay bills with ease.\nFast, secure, and always ready when you are."}
          </p>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-6">
            {user ? (
               <Link 
                to="/purchase/data" 
                className="group bg-indigo-600 text-white px-10 py-6 rounded-2xl font-bold text-sm tracking-wide flex items-center gap-3 hover:bg-white hover:text-slate-900 transition-all shadow-xl shadow-indigo-600/20"
              >
                Buy Data Now <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </Link>
            ) : (
              <Link 
                to="/register" 
                className="group bg-indigo-600 text-white px-10 py-6 rounded-2xl font-bold text-sm tracking-wide flex items-center gap-3 hover:bg-white hover:text-slate-900 transition-all shadow-xl shadow-indigo-600/20"
              >
                Get Started <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
              </Link>
            )}
            
            <a href="#services" className="px-10 py-6 rounded-2xl font-bold text-sm tracking-wide text-white hover:bg-white/5 transition-all flex items-center gap-2 border border-white/5">
                Explore Services <ChevronRight size={16} />
            </a>
          </div>
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-24 bg-white/[0.02] border-y border-white/5 relative">
        <div className="max-w-7xl mx-auto px-6">
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-12">
                {[
                    { label: 'Happy Transactions', val: '5.2M+', icon: <ZapIcon size={16} /> },
                    { label: 'Service Availability', val: '99.98%', icon: <Globe size={16} /> },
                    { label: 'Secure Payments', val: 'Protected', icon: <Shield size={16} /> },
                    { label: 'Instant Delivery', val: '< 2 Seconds', icon: <Cpu size={16} /> },
                ].map((stat, i) => (
                    <div key={i} className="space-y-3">
                        <div className="flex items-center gap-2 text-indigo-400 opacity-60">
                            {stat.icon}
                            <span className="text-[10px] font-bold uppercase tracking-wider">{stat.label}</span>
                        </div>
                        <p className="text-4xl font-bold text-white tracking-tight">{stat.val}</p>
                    </div>
                ))}
            </div>
        </div>
      </section>

      {/* Services Section */}
      <section id="services" className="py-40 px-6 relative overflow-hidden">
        <div className="absolute top-1/2 right-0 w-[500px] h-[500px] bg-indigo-600/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div className="max-w-7xl mx-auto">
          <header className="mb-24 flex flex-col md:flex-row md:items-end justify-between gap-12">
            <div className="space-y-6">
              <span className="text-indigo-500 font-bold text-xs uppercase tracking-widest">Our Features</span>
              <h2 className="text-5xl lg:text-7xl font-bold text-white tracking-tight">Our Services.</h2>
            </div>
            <p className="max-w-md text-slate-400 font-medium leading-relaxed opacity-90">
              Simple and easy ways to manage your digital needs. We provide fast and reliable services you can trust.
            </p>
          </header>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
             {/* Data Gateway */}
             <div className="group bg-slate-900/50 backdrop-blur-sm p-12 rounded-[48px] border border-white/5 hover:border-indigo-500/30 transition-all duration-500 shadow-sm hover:shadow-2xl hover:shadow-indigo-600/5">
                <div className="w-16 h-16 bg-white text-slate-950 rounded-2xl flex items-center justify-center mb-10 shadow-2xl group-hover:scale-110 transition-transform duration-500">
                    <Database size={28} />
                </div>
                <h3 className="text-3xl font-bold text-white mb-6">Data Purchase</h3>
                <p className="text-slate-400 text-sm leading-relaxed mb-10 font-medium">Instantly top up data bundles across all major networks with zero latency.</p>
                <Link to="/purchase/data" className="flex items-center gap-2 text-indigo-400 font-bold text-xs uppercase tracking-wider group-hover:gap-3 transition-all">
                    Buy Now <ArrowRight size={14} />
                </Link>
             </div>

             {/* Airtime Gateway */}
             <div className="group bg-slate-900/50 backdrop-blur-sm p-12 rounded-[48px] border border-white/5 hover:border-emerald-500/30 transition-all duration-500 shadow-sm hover:shadow-2xl hover:shadow-emerald-600/5">
                <div className="w-16 h-16 bg-white text-slate-950 rounded-2xl flex items-center justify-center mb-10 shadow-2xl group-hover:scale-110 transition-transform duration-500">
                    <Smartphone size={28} />
                </div>
                <h3 className="text-3xl font-bold text-white mb-6">Airtime Topup</h3>
                <p className="text-slate-400 text-sm leading-relaxed mb-10 font-medium">Global airtime recharge for MTN, Airtel, Glo, and 9mobile at competitive rates.</p>
                <Link to="/purchase/airtime" className="flex items-center gap-2 text-emerald-500 font-bold text-xs uppercase tracking-wider group-hover:gap-3 transition-all">
                    Top up Now <ArrowRight size={14} />
                </Link>
             </div>

             {/* A2C */}
             <div className="group bg-slate-900/50 backdrop-blur-sm p-12 rounded-[48px] border border-white/5 hover:border-amber-500/30 transition-all duration-500 shadow-sm hover:shadow-2xl hover:shadow-amber-600/5">
                <div className="w-16 h-16 bg-white text-slate-950 rounded-2xl flex items-center justify-center mb-10 shadow-2xl group-hover:scale-110 transition-transform duration-500">
                    <TrendingUp size={28} />
                </div>
                <h3 className="text-3xl font-bold text-white mb-6">Airtime to Cash</h3>
                <p className="text-slate-400 text-sm leading-relaxed mb-10 font-medium">Convert your mobile airtime back into wallet funds instantly and securely.</p>
                <Link to="/a2c" className="flex items-center gap-2 text-amber-500 font-bold text-xs uppercase tracking-wider group-hover:gap-3 transition-all">
                    Convert Now <ArrowRight size={14} />
                </Link>
             </div>
          </div>
        </div>
      </section>

      {/* Premium Products Collection (From home.blade.php) */}
      <section className="py-40 px-6 bg-slate-950/50">
        <div className="max-w-7xl mx-auto">
            <header className="mb-20 text-center space-y-4">
                <span className="text-indigo-500 font-bold text-xs uppercase tracking-widest">The Shop</span>
                <h2 className="text-5xl lg:text-6xl font-bold text-white tracking-tight">Premium Collection.</h2>
                <p className="max-w-2xl mx-auto text-slate-400 font-medium">Explore our curated selection of high-end digital products and services.</p>
            </header>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                {products.map((p) => (
                    <div key={p.id} className="group bg-white/5 p-8 rounded-[40px] border border-white/5 hover:bg-white/[0.08] transition-all duration-500">
                        <div className="aspect-square bg-slate-800 rounded-3xl mb-8 relative overflow-hidden">
                           { (p.image || p.image_url) ? (
                             <img src={p.image || p.image_url} alt={p.name} className="w-full h-full object-cover" />
                           ) : (
                             <div className="w-full h-full flex items-center justify-center text-slate-700">
                                <ShoppingBag size={48} />
                             </div>
                           )}
                           <div className="absolute top-4 right-4 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-wider border border-white/10">
                              {p.category}
                           </div>
                        </div>
                        <h3 className="text-xl font-bold text-white mb-2 leading-tight">{p.name}</h3>
                        <p className="text-indigo-400 font-bold text-sm mb-6">₦{Number(p.price || 0).toLocaleString()}</p>
                        <button className="w-full py-4 bg-white/10 hover:bg-white hover:text-slate-950 text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all border border-white/5">
                            Purchase Item
                        </button>
                    </div>
                ))}
            </div>
            
            <div className="mt-20 text-center">
                 <Link to="/shop" className="inline-flex items-center gap-2 text-slate-400 hover:text-white font-bold transition-colors">
                    View Entire Catalog <ArrowRight size={18} />
                 </Link>
            </div>
        </div>
      </section>

      {/* How It Works (Migration) */}
      <section className="py-40 px-6 relative">
        <div className="max-w-7xl mx-auto">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
                <div className="space-y-12">
                     <span className="text-indigo-500 font-bold text-xs uppercase tracking-widest">Simple Process</span>
                     <h2 className="text-5xl lg:text-7xl font-bold text-white tracking-tight leading-tight">How It <br /> Works.</h2>
                     <div className="space-y-10">
                        {[
                            { t: 'Register', d: 'Create your account in seconds and join thousands of happy users.' },
                            { t: 'Fund Wallet', d: 'Easily add money to your wallet using any of our secure payment methods.' },
                            { t: 'Enjoy Services', d: 'Select any service, make payment, and get value delivered instantly.' }
                        ].map((step, i) => (
                            <div key={i} className="flex gap-8 group">
                                <div className="text-7xl font-bold text-white/5 select-none leading-none transition-colors group-hover:text-indigo-600/20">
                                    0{i + 1}
                                </div>
                                <div className="space-y-2 pt-2">
                                    <h4 className="font-bold text-2xl text-white tracking-tight">{step.t}</h4>
                                    <p className="text-slate-400 font-medium leading-relaxed max-w-sm">{step.d}</p>
                                </div>
                            </div>
                        ))}
                     </div>
                </div>

                <div className="relative">
                    <div className="absolute inset-0 bg-indigo-600/10 blur-[100px] rounded-full animate-pulse"></div>
                    <div className="relative bg-slate-900 border border-white/5 p-12 lg:p-16 rounded-[64px] shadow-2xl">
                         <div className="space-y-10">
                            <div className="flex justify-between items-center">
                                <div className="flex gap-2">
                                    {[1,2,3].map(i => <div key={i} className="w-2.5 h-2.5 rounded-full bg-slate-800"></div>)}
                                </div>
                                <div className="text-[10px] font-bold text-slate-700 uppercase tracking-widest">S3C Secure</div>
                            </div>
                            <div className="space-y-4 font-mono text-sm leading-relaxed">
                                <p className="text-emerald-400">$ s3c start --action signup</p>
                                <p className="text-slate-500">&gt; Creating account... [SUCCESS]</p>
                                <p className="text-slate-500">&gt; Connecting to server... [LIVE]</p>
                                <p className="text-indigo-400">$ s3c buy data --amount 1GB</p>
                                <p className="text-slate-500">&gt; Processing payment... </p>
                                <p className="text-emerald-500">&gt; Data Received. 200 OK</p>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
      </section>

      {/* CEO Section Spotlight */}
      <section className="py-40 px-6 bg-white/[0.02]">
        <div className="max-w-5xl mx-auto">
             <div className="bg-slate-900 rounded-[64px] p-12 lg:p-24 border border-white/5 shadow-2xl relative overflow-hidden text-center space-y-12">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-indigo-600/10 blur-[100px] rounded-full pointer-events-none"></div>
                
                {ceoData?.image_url ? (
                    <img src={`http://localhost:8000${ceoData.image_url}`} alt="CEO" className="w-24 h-24 rounded-3xl mx-auto object-cover border-4 border-slate-950 shadow-2xl mb-8" />
                ) : (
                    <div className="w-24 h-24 bg-indigo-600 text-white rounded-3xl mx-auto flex items-center justify-center border-4 border-slate-950 shadow-2xl mb-8">
                         <Users size={40} />
                    </div>
                )}

                <p className="text-2xl lg:text-3xl font-medium text-slate-200 leading-relaxed italic opacity-90">
                    "{ceoData?.content || "We are dedicated to providing the easiest way for you to stay connected. Making technology simple for everyone."}"
                </p>

                <div className="space-y-2">
                    <h4 className="text-xl font-bold text-white tracking-tight uppercase">{ceoData?.meta?.name || "Henry D."}</h4>
                    <p className="text-indigo-500 font-bold text-xs uppercase tracking-widest">{ceoData?.meta?.position || "Founder & CEO"}</p>
                </div>

                <div className="pt-10 flex justify-center opacity-30">
                    <div className="w-40 h-10 border-b-2 border-slate-700 border-dashed"></div>
                </div>
             </div>
        </div>
      </section>

      {/* Team Section */}
      <section className="py-40 px-6">
        <div className="max-w-7xl mx-auto">
            <header className="mb-24 text-center">
                <span className="text-indigo-500 font-bold text-xs uppercase tracking-widest mb-4 block">The Team</span>
                <h2 className="text-5xl font-bold text-white tracking-tight">Meet Our Experts.</h2>
            </header>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                {[
                    { n: 'Alexander J.', r: 'Network Lead', i: <Database /> },
                    { n: 'Sarah W.', r: 'UI Architect', i: <Globe /> },
                    { n: 'Marcus K.', r: 'Security Eng', i: <Shield /> },
                    { n: 'Elena R.', r: 'Fullstack Dev', i: <Zap /> },
                ].map((member, i) => (
                    <div key={i} className="group p-8 rounded-[40px] bg-slate-900 border border-white/5 hover:border-indigo-500/30 transition-all text-center space-y-6">
                        <div className="w-20 h-20 bg-white/5 rounded-3xl mx-auto flex items-center justify-center text-slate-600 group-hover:bg-indigo-600 transition-colors">
                            {member.i}
                        </div>
                        <div>
                            <h4 className="text-lg font-bold text-white">{member.n}</h4>
                            <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{member.r}</p>
                        </div>
                        <div className="flex justify-center gap-4 opacity-0 group-hover:opacity-100 transition-opacity">
                             {[1,2,3].map(j => <div key={j} className="w-2 h-2 rounded-full bg-indigo-500/50"></div>)}
                        </div>
                    </div>
                ))}
            </div>
        </div>
      </section>

      {/* API / Developer Section */}
      <section className="py-20 px-6 bg-slate-950 border-y border-white/5">
        <div className="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-16">
            <div className="space-y-6 max-w-xl">
                 <h3 className="text-4xl font-bold text-white tracking-tight">Build with our API.</h3>
                 <p className="text-slate-400 font-medium leading-relaxed">Access our full range of digital services via our powerful and easy-to-use developer API.</p>
                 <div className="flex flex-wrap gap-4 pt-4">
                    {['REST API', 'Webhooks', 'Developers', 'Stable', 'Fast'].map(tech => (
                        <span key={tech} className="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">{tech}</span>
                    ))}
                 </div>
            </div>
            <Link to="#" className="px-12 py-7 bg-white text-slate-900 rounded-[32px] font-bold text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all shadow-premium flex items-center gap-3">
                <Code2 size={18} /> API Documentation
            </Link>
        </div>
      </section>

      {/* Network Logos */}
      <section className="py-24 px-6 grayscale opacity-40 hover:grayscale-0 hover:opacity-100 transition-all">
        <div className="max-w-7xl mx-auto flex flex-wrap justify-around items-center gap-12">
            {['MTN Business', 'Airtel Enterprise', 'GLO Global', '9Mobile Network'].map(n => (
                <span key={n} className="text-2xl font-bold text-slate-500 tracking-tighter whitespace-nowrap">{n}</span>
            ))}
        </div>
      </section>

      {/* Final CTA */}
      <section className="py-40 px-6 relative overflow-hidden">
        <div className="max-w-4xl mx-auto text-center space-y-12 relative z-10">
            <h2 className="text-5xl lg:text-7xl font-bold text-white tracking-tight leading-tight">Ready to <br /> Get Started?</h2>
            <p className="text-slate-400 text-lg font-medium">Join thousands of users enjoying seamless digital services every day.</p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-6">
                 {user ? (
                    <Link to="/purchase/data" className="w-full sm:w-auto bg-indigo-600 text-white px-12 py-7 rounded-[32px] font-bold text-sm tracking-wide hover:bg-white hover:text-slate-900 transition-all shadow-2xl">
                        Go to Dashboard
                    </Link>
                 ) : (
                    <Link to="/register" className="w-full sm:w-auto bg-indigo-600 text-white px-12 py-7 rounded-[32px] font-bold text-sm tracking-wide hover:bg-white hover:text-slate-900 transition-all shadow-2xl">
                        Register Now
                    </Link>
                 )}
                 <Link to="#" className="w-full sm:w-auto flex items-center justify-center gap-2 text-slate-500 hover:text-white font-bold transition-all px-8">
                    <PhoneCall size={18} /> Contact Support
                 </Link>
            </div>
        </div>
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-600/5 blur-[120px] rounded-full pointer-events-none"></div>
      </section>
    </WebsiteLayout>
  );
};

export default LandingPage;
