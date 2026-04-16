import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import client from '../api/client';
import { useAuth } from '../context/AuthContext';
import DashboardLayout from '../layouts/DashboardLayout';
import { Database, Smartphone, CheckCircle2, AlertCircle, ArrowRight } from 'lucide-react';

interface DataPlan {
  id: number;
  network: string;
  name: string;
  volume: string;
  selling_price: string;
  validity: string;
}

const DataPurchase: React.FC = () => {
  const [plans, setPlans] = useState<DataPlan[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedNetwork, setSelectedNetwork] = useState<string | null>(null);
  const [selectedPlan, setSelectedPlan] = useState<number | null>(null);
  const [phone, setPhone] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [status, setStatus] = useState<{ success: boolean; message: string } | null>(null);
  const { refreshUser } = useAuth();

  const networks = ['MTN', 'Airtel', 'Glo', '9Mobile'];

  useEffect(() => {
    client.get('/vtu/data/plans')
      .then(res => setPlans(res.data.plans))
      .catch(err => console.error('Failed to load plans', err))
      .finally(() => setLoading(false));
  }, []);

  const handlePurchase = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedNetwork || !selectedPlan || phone.length !== 11) return;

    setSubmitting(true);
    setStatus(null);

    try {
      const { data } = await client.post('/vtu/data/purchase', {
        phone,
        network: selectedNetwork,
        plan_id: selectedPlan
      });
      setStatus({ success: true, message: data.message });
      setPhone('');
      setSelectedPlan(null);
      await refreshUser(); // Update wallet balance
    } catch (err: any) {
      setStatus({ success: false, message: err.response?.data?.message || 'Transaction failed' });
    } finally {
      setSubmitting(false);
    }
  };

  const filteredPlans = plans.filter(p => !selectedNetwork || p.network.toUpperCase().includes(selectedNetwork.toUpperCase()));

  if (loading) return (
    <DashboardLayout>
        <div className="flex justify-center items-center h-[50vh] font-bold text-slate-500 text-sm">
            Loading plans...
        </div>
    </DashboardLayout>
  );

  return (
    <DashboardLayout>
      <section className="py-2">
        <div className="max-w-7xl mx-auto">
          <header className="mb-10">
            <h1 className="text-2xl font-bold text-white tracking-tight">Purchase Data</h1>
            <p className="text-slate-500 text-sm mt-1">Select your network and choose a bundle to continue.</p>
          </header>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div className="lg:col-span-12">
               {status && (
                  <div className={`p-6 rounded-2xl mb-6 flex items-center gap-5 border shadow-lg animate-in slide-in-from-top duration-300 ${status.success ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'}`}>
                    <div className={status.success ? 'text-emerald-500' : 'text-rose-500'}>
                        {status.success ? <CheckCircle2 size={24} /> : <AlertCircle size={24} />}
                    </div>
                    <div>
                        <p className="text-xs font-bold uppercase tracking-wider mb-1">{status.success ? 'Success' : 'Transaction Error'}</p>
                        <p className="text-sm font-semibold">{status.message}</p>
                    </div>
                  </div>
                )}
            </div>

            {/* Form Section */}
            <div className="lg:col-span-8 bg-slate-800/20 p-8 lg:p-10 rounded-3xl border border-white/5">
                <form onSubmit={handlePurchase} className="space-y-8">
                  <div className="space-y-4">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">1. Select Network</label>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                      {networks.map(n => (
                        <button
                          key={n}
                          type="button"
                          onClick={() => { setSelectedNetwork(n); setSelectedPlan(null); }}
                          className={`py-6 rounded-2xl border-2 transition-all font-bold text-xs tracking-wider ${selectedNetwork === n ? 'border-indigo-600 bg-indigo-600 text-white shadow-lg' : 'border-white/5 bg-slate-900 text-slate-500 hover:border-white/10 hover:text-slate-300'}`}
                        >
                          {n}
                        </button>
                      ))}
                    </div>
                  </div>

                  <div className="space-y-4">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">2. Phone Number</label>
                    <div className="relative group">
                       <Smartphone className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-indigo-400 transition-colors" size={18} />
                       <input
                        type="tel"
                        maxLength={11}
                        className="w-full bg-slate-900 border border-white/10 rounded-2xl p-4 pl-12 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/50 outline-none transition-all font-bold text-sm text-white placeholder:text-slate-700"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value.replace(/\D/g, ''))}
                        placeholder="0810333XXXX"
                        required
                      />
                    </div>
                  </div>

                  <div className="space-y-4">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">3. Data Plan</label>
                    <div className="relative group">
                        <select 
                            className="w-full bg-slate-900 border border-white/10 rounded-2xl p-4 appearance-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500/50 outline-none transition-all font-bold text-sm text-slate-300 disabled:opacity-30"
                            value={selectedPlan || ''}
                            onChange={(e) => setSelectedPlan(Number(e.target.value))}
                            disabled={!selectedNetwork}
                        >
                            <option value="" className="bg-slate-900">{selectedNetwork ? '-- Select Plan --' : '-- Choose Network First --'}</option>
                            {filteredPlans.map(p => {
                                // Strip provider cost from name (e.g. "1.2GB = N495 (1 month)" → "1.2GB (1 month)")
                                const cleanName = p.name.replace(/\s*=\s*N[\d,.]+/gi, '').trim();
                                return (
                                    <option key={p.id} value={p.id} className="bg-slate-900">
                                        {cleanName}{p.volume ? ` (${p.volume})` : ''} - ₦{Number(p.selling_price).toLocaleString()}
                                    </option>
                                );
                            })}
                        </select>
                        <div className="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-600 group-focus-within:text-indigo-400 transition-colors">
                            <ArrowRight size={18} className="rotate-90" />
                        </div>
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={submitting || !selectedPlan || phone.length !== 11}
                    className="w-full bg-indigo-600 hover:bg-white hover:text-slate-950 text-white font-bold py-5 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 disabled:opacity-20 disabled:cursor-not-allowed transform active:scale-95"
                  >
                    {submitting ? 'Processing...' : 'Complete Purchase'}
                    <Database size={18} />
                  </button>
                </form>
            </div>

            {/* Sidebar Column */}
            <div className="lg:col-span-4 space-y-6">
                <div className="bg-slate-900 p-8 rounded-3xl border border-white/5 shadow-lg space-y-8">
                    <h3 className="text-lg font-bold text-white tracking-tight">Quick Information</h3>
                    <div className="space-y-6">
                        {[
                            { t: 'Instant Delivery', d: 'Bundles are credited within 30 seconds of confirmation.' },
                            { t: 'Secure Payment', d: 'Your transactions are protected by industry standards.' }
                        ].map(item => (
                            <div key={item.t} className="flex gap-4">
                                <div className="w-10 h-10 rounded-xl bg-indigo-600/10 text-indigo-400 flex items-center justify-center flex-shrink-0">
                                    <CheckCircle2 size={18} />
                                </div>
                                <div className="space-y-1">
                                    <h4 className="font-bold text-sm text-slate-200 leading-tight">{item.t}</h4>
                                    <p className="text-xs text-slate-500 leading-relaxed font-medium">{item.d}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                <Link to="/purchase/airtime" className="block bg-white/5 hover:bg-white/10 border border-white/5 p-6 rounded-2xl transition-all">
                    <div className="flex items-center gap-4">
                        <div className="w-10 h-10 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center">
                            <Smartphone size={20} />
                        </div>
                        <div>
                            <p className="text-xs font-bold text-slate-600 uppercase tracking-wider leading-none mb-1">Need Airtime?</p>
                            <p className="text-sm font-bold text-slate-300">Switch to Airtime Top-up</p>
                        </div>
                    </div>
                </Link>
            </div>
          </div>
        </div>
      </section>
    </DashboardLayout>
  );
};

export default DataPurchase;
