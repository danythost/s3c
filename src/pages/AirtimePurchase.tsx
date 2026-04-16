import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import client from '../api/client';
import { useAuth } from '../context/AuthContext';
import DashboardLayout from '../layouts/DashboardLayout';
import { Smartphone, CheckCircle2, AlertCircle, Coins, ArrowRight } from 'lucide-react';

const AirtimePurchase: React.FC = () => {
  const [network, setNetwork] = useState<string | null>(null);
  const [amount, setAmount] = useState('');
  const [phone, setPhone] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [status, setStatus] = useState<{ success: boolean; message: string } | null>(null);
  const { refreshUser } = useAuth();

  const networks = ['MTN', 'Airtel', 'Glo', '9Mobile'];

  const handlePurchase = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!network || !amount || phone.length !== 11) return;

    setSubmitting(true);
    setStatus(null);

    try {
      const { data } = await client.post('/vtu/airtime/purchase', {
        phone,
        network,
        amount: Number(amount)
      });
      setStatus({ success: true, message: data.message });
      setPhone('');
      setAmount('');
      await refreshUser(); // Update wallet balance
    } catch (err: any) {
      setStatus({ success: false, message: err.response?.data?.message || 'Transaction failed' });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <DashboardLayout>
      <section className="py-2">
        <div className="max-w-7xl mx-auto">
          <header className="mb-10">
            <h1 className="text-2xl font-bold text-white tracking-tight">Buy Airtime</h1>
            <p className="text-slate-500 text-sm mt-1">Quickly top up your phone credit across all major networks.</p>
          </header>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div className="lg:col-span-12">
               {status && (
                  <div className={`p-6 rounded-2xl mb-6 flex items-center gap-5 border shadow-lg ${status.success ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'}`}>
                    <div className={status.success ? 'text-emerald-500' : 'text-rose-500'}>
                        {status.success ? <CheckCircle2 size={24} /> : <AlertCircle size={24} />}
                    </div>
                    <div>
                        <p className="text-xs font-bold uppercase tracking-wider mb-1">{status.success ? 'Success' : 'Purchase Failed'}</p>
                        <p className="text-sm font-semibold">{status.message}</p>
                    </div>
                  </div>
                )}
            </div>

            {/* Form Section */}
            <div className="lg:col-span-8 bg-slate-800/20 p-8 lg:p-10 rounded-3xl border border-white/5">
                <form onSubmit={handlePurchase} className="space-y-8">
                  <div className="space-y-4">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">1. Select Carrier</label>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                      {networks.map(n => (
                        <button
                          key={n}
                          type="button"
                          onClick={() => setNetwork(n)}
                          className={`py-6 rounded-2xl border-2 transition-all font-bold text-xs tracking-wider ${network === n ? 'border-emerald-500 bg-emerald-500 text-white shadow-lg' : 'border-white/5 bg-slate-900 text-slate-500 hover:border-white/10 hover:text-slate-300'}`}
                        >
                          {n}
                        </button>
                      ))}
                    </div>
                  </div>

                  <div className="space-y-4">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">2. Destination Number</label>
                    <div className="relative group">
                       <Smartphone className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-emerald-500 transition-colors" size={18} />
                       <input
                        type="tel"
                        maxLength={11}
                        className="w-full bg-slate-900 border border-white/10 rounded-2xl p-4 pl-12 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 outline-none transition-all font-bold text-sm text-white placeholder:text-slate-700"
                        value={phone}
                        onChange={(e) => setPhone(e.target.value.replace(/\D/g, ''))}
                        placeholder="090XXXXXXXX"
                        required
                      />
                    </div>
                  </div>

                  <div className="space-y-4">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">3. Top-up Amount (Min ₦100)</label>
                    <div className="relative group">
                       <span className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-lg">₦</span>
                       <input
                        type="number"
                        min="100"
                        className="w-full bg-slate-900 border border-white/10 rounded-2xl p-4 pl-10 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500/50 outline-none transition-all font-bold text-lg text-white placeholder:text-slate-700"
                        value={amount}
                        onChange={(e) => setAmount(e.target.value)}
                        placeholder="1000"
                        required
                      />
                    </div>
                  </div>

                  <button
                    type="submit"
                    disabled={submitting || !network || !amount || phone.length !== 11}
                    className="w-full bg-slate-900 hover:bg-emerald-600 text-white font-bold py-5 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 active:scale-95 disabled:opacity-30"
                  >
                    {submitting ? 'Processing...' : 'Complete Top-up'}
                    <Coins size={20} />
                  </button>
                </form>
            </div>

            {/* Info Sidebar */}
            <div className="lg:col-span-4 space-y-6">
                <div className="bg-emerald-600 p-8 lg:p-10 rounded-3xl text-white shadow-xl space-y-6">
                    <div className="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10">
                        <Smartphone size={32} />
                    </div>
                    <div className="space-y-2">
                        <h3 className="text-xl font-bold tracking-tight">Instant Payout</h3>
                        <p className="text-emerald-50 text-sm font-medium leading-relaxed opacity-90">
                           Your airtime is delivered within seconds of a successful transaction. Verified across all carriers.
                        </p>
                    </div>
                </div>

                <Link to="/purchase/data" className="block bg-white/5 hover:bg-white/10 border border-white/5 p-6 rounded-2xl transition-all">
                    <div className="flex items-center gap-4">
                        <div className="w-10 h-10 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center">
                            <ArrowRight size={20} />
                        </div>
                        <div>
                            <p className="text-xs font-bold text-slate-600 uppercase tracking-wider leading-none mb-1">Need Data?</p>
                            <p className="text-sm font-bold text-slate-300">Switch to Data Purchase</p>
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

export default AirtimePurchase;
