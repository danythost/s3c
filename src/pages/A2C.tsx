import React, { useState } from 'react';
import DashboardLayout from '../layouts/DashboardLayout';
import { 
  Zap, 
  ShieldCheck, 
  Clock, 
  ExternalLink,
  Smartphone,
  Info,
  Maximize2,
  X 
} from 'lucide-react';

const A2C: React.FC = () => {
  const [isPortalOpen, setIsPortalOpen] = useState(false);
  const [isLoaded, setIsLoaded] = useState(false);

  const guidelines = [
    'Minimum amount per transaction is ₦100.',
    'Send airtime only from the phone number provided.',
    'Only send the exact amount requested by the portal.',
    'Transactions are processed within 5 - 30 minutes.'
  ];

  return (
    <DashboardLayout>
      <header className="mb-10">
        <h1 className="text-3xl font-bold text-white tracking-tight leading-none">
            Airtime to Cash
        </h1>
        <p className="text-slate-500 text-sm mt-1">Convert your mobile airtime into wallet balance instantly.</p>
      </header>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div className="lg:col-span-12">
            <div className="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[40px] p-10 lg:p-14 text-white relative overflow-hidden shadow-xl border border-white/5">
                <div className="absolute top-0 right-0 w-96 h-96 bg-white/5 blur-[100px] rounded-full -mr-48 -mt-48"></div>
                
                <div className="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div className="space-y-8">
                        <div className="inline-flex items-center gap-2 bg-black/20 px-4 py-2 rounded-full border border-white/10 text-xs font-bold uppercase tracking-wider leading-none">
                            <ShieldCheck size={14} className="text-emerald-400" />
                            Secure Transfer Protocol
                        </div>
                        <h2 className="text-4xl font-bold tracking-tight leading-tight">
                            Liquidate excess airtime instantly.
                        </h2>
                        <p className="text-indigo-100 font-medium text-sm leading-relaxed max-w-md opacity-80">
                            Connect to the S3C Gateway to convert airtime from any major network into real-time wallet funds.
                        </p>
                        <button 
                            onClick={() => setIsPortalOpen(true)}
                            className="bg-white text-slate-900 px-10 py-5 rounded-2xl font-bold text-sm transition-all shadow-xl flex items-center gap-3 active:scale-95"
                        >
                            <Zap size={18} /> Open Conversion Portal
                        </button>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div className="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 lg:p-8 space-y-3 shadow-lg">
                           <div className="w-10 h-10 bg-white/10 text-white rounded-xl flex items-center justify-center">
                               <Clock size={20} />
                           </div>
                           <h3 className="font-bold text-sm">Fast Processing</h3>
                           <p className="text-xs font-medium text-indigo-100/60 leading-relaxed">Usually processed in 5-10 minutes.</p>
                        </div>
                        <div className="bg-white/10 backdrop-blur-xl border border-white/10 rounded-3xl p-6 lg:p-8 space-y-3 shadow-lg">
                           <div className="w-10 h-10 bg-white/10 text-white rounded-xl flex items-center justify-center">
                               <Smartphone size={20} />
                           </div>
                           <h3 className="font-bold text-sm">All Networks</h3>
                           <p className="text-xs font-medium text-indigo-100/60 leading-relaxed">Support for MTN, Airtel, GLO, and 9mobile.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div className="lg:col-span-8">
            <div className="bg-slate-800/20 p-10 rounded-[40px] border border-white/5">
                <header className="flex items-center justify-between mb-8">
                    <div className="flex items-center gap-3">
                        <Info className="text-indigo-400" size={20} />
                        <h3 className="text-xs font-bold tracking-wider uppercase text-slate-400">Conversion Guidelines</h3>
                    </div>
                </header>
                <div className="space-y-4">
                    {guidelines.map((text, i) => (
                        <div key={i} className="flex gap-4 p-5 bg-slate-900 border border-white/5 rounded-2xl group hover:border-white/10 transition-all">
                            <div className="w-6 h-6 rounded-full bg-indigo-600 font-bold text-xs flex items-center justify-center text-white shrink-0">
                                {i + 1}
                            </div>
                            <p className="text-xs font-bold text-slate-400 leading-relaxed group-hover:text-slate-200 transition-colors">
                                {text}
                            </p>
                        </div>
                    ))}
                </div>
            </div>
        </div>

        <div className="lg:col-span-4 space-y-6">
            <div className="bg-slate-900 border border-white/5 p-10 rounded-[40px] flex flex-col items-center justify-center text-center space-y-6">
                <div className="w-16 h-16 bg-white/5 text-slate-500 rounded-3xl flex items-center justify-center border border-white/5">
                    <ExternalLink size={28} />
                </div>
                <div className="space-y-2">
                    <h4 className="text-sm font-bold text-white uppercase tracking-wider">Gateway Secure</h4>
                    <p className="text-xs font-bold text-slate-600 uppercase tracking-wider leading-relaxed">
                        Powered by VTUAfrica Infrastructure.
                    </p>
                </div>
            </div>
        </div>
      </div>

      {/* Portal Overlay */}
      {isPortalOpen && (
        <div className="fixed inset-0 z-[100] flex flex-col bg-slate-950 animate-in fade-in duration-300">
            {/* Header */}
            <header className="h-20 bg-slate-900 border-b border-white/10 flex items-center justify-between px-8 shrink-0 shadow-2xl">
                <div className="flex items-center gap-4">
                    <div className="w-10 h-10 bg-white/5 text-indigo-400 border border-white/10 rounded-xl flex items-center justify-center">
                        <Maximize2 size={24} />
                    </div>
                    <div>
                        <h3 className="text-white font-bold tracking-tight text-lg">Conversion Portal</h3>
                        <p className="text-[10px] font-bold text-slate-500 uppercase tracking-wider leading-none mt-1">Authorized Gateway • Stable Link</p>
                    </div>
                </div>
                
                <div className="flex items-center gap-4">
                    <a 
                      href="https://vtuafrica.com.ng/portal/" 
                      target="_blank" 
                      rel="noopener noreferrer"
                      className="hidden sm:flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-xs font-bold uppercase tracking-wider text-white transition-all active:scale-95"
                    >
                        Browser Link <ExternalLink size={14} />
                    </a>
                    <button 
                        onClick={() => { setIsPortalOpen(false); setIsLoaded(false); }}
                        className="w-12 h-12 bg-white/5 hover:bg-rose-500/20 hover:text-rose-500 border border-white/10 rounded-xl text-slate-400 flex items-center justify-center transition-all stroke-[3px]"
                    >
                        <X size={24} />
                    </button>
                </div>
            </header>

            {/* Content Buffer */}
            <div className="flex-1 relative bg-white">
                {!isLoaded && (
                    <div className="absolute inset-0 flex flex-col items-center justify-center bg-slate-950 z-50">
                        <div className="w-16 h-16 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        <div className="mt-8 text-center space-y-2">
                             <h4 className="text-white text-sm font-bold uppercase tracking-[0.2em] animate-pulse">Connecting...</h4>
                             <p className="text-slate-600 text-xs font-bold uppercase tracking-wider">Bridging secure connection to S3C Mainnet</p>
                        </div>
                    </div>
                )}
                <iframe 
                    src="https://vtuafrica.com.ng/portal/" 
                    className="w-full h-full border-0"
                    onLoad={() => setIsLoaded(true)}
                    title="A2C Portal"
                    allow="payment; clipboard-write; storage-access"
                />
            </div>
            
            {/* Footer */}
            <footer className="p-5 bg-slate-900 border-t border-white/5 flex flex-col sm:flex-row justify-between items-center px-10 gap-4 shrink-0">
                <p className="text-xs font-bold text-slate-600 uppercase tracking-wider">© 2026 S3C Core Systems</p>
                <div className="flex items-center gap-3">
                    <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span className="text-xs font-bold text-emerald-500/80 uppercase tracking-wider">Encrypted Connection</span>
                </div>
            </footer>
        </div>
      )}
    </DashboardLayout>
  );
};

export default A2C;
