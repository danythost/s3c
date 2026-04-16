import React, { useEffect, useState } from 'react';
import client from '../api/client';
import { useAuth } from '../context/AuthContext';
import DashboardLayout from '../layouts/DashboardLayout';
import { 
  History, 
  ArrowUpRight, 
  ArrowDownLeft, 
  Copy, 
  CheckCircle,
  Building2,
  User as UserIcon,
  Search,
  Filter,
  Eye,
  EyeOff,
  RotateCw
} from 'lucide-react';

interface Transaction {
  id: number;
  source: string;
  type: 'credit' | 'debit';
  amount: number;
  status: string;
  created_at: string;
  reference: string;
}

const Wallet: React.FC = () => {
  const { user, refreshUser } = useAuth();
  const [transactions, setTransactions] = useState<Transaction[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [copySuccess, setCopySuccess] = useState(false);
  const [showAccount, setShowAccount] = useState(false);
  const [fundingCharge, setFundingCharge] = useState(0);

  useEffect(() => {
    // Fetch ledger
    client.get('/wallet/history')
      .then(res => setTransactions(res.data.transactions.data))
      .catch(err => console.error('Failed to load ledger', err))
      .finally(() => setLoading(false));

    // Fetch public settings for funding charge
    client.get('/public/settings')
      .then(res => {
          if (res.data.success && res.data.data.wallet_funding_charge) {
              setFundingCharge(Number(res.data.data.wallet_funding_charge));
          }
      })
      .catch(err => console.error('Failed to load settings', err));
  }, []);

  const handleCopy = (text: string) => {
    navigator.clipboard.writeText(text);
    setCopySuccess(true);
    setTimeout(() => setCopySuccess(false), 2000);
  };

  const va = user?.virtual_account;

  return (
    <DashboardLayout>
      <header className="mb-10">
        <h1 className="text-2xl font-bold text-white tracking-tight">Wallet & History</h1>
        <p className="text-slate-500 text-sm mt-1">Manage your funds and view your complete transaction ledger.</p>
      </header>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Funding Card */}
        <div className="lg:col-span-12">
            <div className="bg-slate-900 border border-white/5 rounded-[40px] p-8 lg:p-12 relative overflow-hidden shadow-2xl">
                <div className="absolute top-0 right-0 w-96 h-96 bg-indigo-600/5 blur-[100px] rounded-full -mr-48 -mt-48"></div>
                
                <div className="relative z-10 flex flex-col lg:flex-row justify-between items-center gap-10">
                    <div className="space-y-6 flex-1">
                        <div className="inline-flex items-center gap-2 bg-indigo-500/10 px-4 py-2 rounded-full border border-indigo-500/20 text-xs font-bold text-indigo-400 uppercase tracking-wider leading-none">
                            Virtual Account Funding
                        </div>
                        <h2 className="text-3xl font-bold text-white tracking-tight">Deposit Funds Easily</h2>
                        <p className="text-slate-400 text-sm font-medium leading-relaxed max-w-lg">
                            Transfer money to your unique bank account below to top up your S3C wallet instantly. Your balance updates automatically.
                        </p>
                    </div>

                    <div className="w-full lg:w-[450px] bg-slate-800 border border-white/10 rounded-3xl p-8 shadow-2xl space-y-6">
                        <div className="flex justify-between items-start">
                           <div className="space-y-1">
                               <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Bank Name</p>
                               <div className="flex items-center gap-2 text-white">
                                   <Building2 size={16} className="text-indigo-400" />
                                   <span className="font-bold text-lg">{va?.bank_name || 'STERLING BANK'}</span>
                               </div>
                           </div>
                           <div className="px-3 py-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 rounded-lg text-[10px] font-bold uppercase">
                               Live
                           </div>
                        </div>

                        <div className="space-y-1">
                             <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Account Number</p>
                            <div className="flex items-center justify-between bg-slate-900 p-4 rounded-2xl border border-white/5 group">
                                 <span className="text-2xl font-bold text-white tracking-widest font-mono">
                                    {va?.account_number
                                      ? (showAccount ? va.account_number : '••••••••••')
                                      : <span className="text-slate-600 text-sm font-semibold italic">Not assigned yet</span>
                                    }
                                </span>
                                <div className="flex items-center gap-1">
                                    {va?.account_number && (
                                        <button
                                            onClick={() => setShowAccount(v => !v)}
                                            className="p-2 hover:bg-white/10 rounded-xl transition-all text-slate-500 hover:text-indigo-400"
                                            title={showAccount ? 'Hide account number' : 'Reveal account number'}
                                        >
                                            {showAccount ? <EyeOff size={20} /> : <Eye size={20} />}
                                        </button>
                                    )}
                                    <button 
                                        onClick={() => handleCopy(va?.account_number || '')}
                                        className="p-2 hover:bg-white/10 rounded-xl transition-all text-slate-500 hover:text-white"
                                        title="Copy account number"
                                    >
                                        {copySuccess ? <CheckCircle size={20} className="text-emerald-500" /> : <Copy size={20} />}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div className="space-y-1">
                             <p className="text-xs font-bold text-slate-500 uppercase tracking-wider">Account Name</p>
                            <div className="flex items-center justify-between gap-2 text-slate-200">
                                <div className="flex items-center gap-2">
                                    <UserIcon size={16} className="text-slate-500" />
                                    <span className="font-bold text-sm">FLW/S3C - {user?.name.toUpperCase()}</span>
                                </div>
                                <button
                                    onClick={async () => {
                                        setRefreshing(true);
                                        try {
                                            const res = await client.post('/wallet/refresh');
                                            if (res.data.success) {
                                                await refreshUser();
                                                // Reload ledger directly
                                                const historyRes = await client.get('/wallet/history');
                                                setTransactions(historyRes.data.transactions.data);
                                            }
                                        } catch (e) {
                                            console.error('Refresh failed', e);
                                        } finally {
                                            setRefreshing(false);
                                        }
                                    }}
                                    disabled={refreshing}
                                    className="px-3 py-1.5 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-xl text-[10px] font-bold flex items-center gap-2 transition-all disabled:opacity-50 border border-white/5"
                                >
                                    <RotateCw size={12} className={refreshing ? 'animate-spin' : ''} />
                                    {refreshing ? 'SYNCING...' : 'REFRESH BALANCE'}
                                </button>
                            </div>
                        </div>

                        {fundingCharge > 0 && (
                            <div className="mt-4 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl flex items-start gap-3">
                                <div className="text-amber-500 mt-0.5">
                                    <CheckCircle size={14} />
                                </div>
                                <div>
                                    <p className="text-xs font-semibold text-amber-500">Processing Fee</p>
                                    <p className="text-[10px] text-amber-500/70 mt-0.5 leading-relaxed">
                                        A flat service charge of <span className="font-bold text-amber-400">₦{fundingCharge}</span> is deducted from every successful wallet deposit.
                                    </p>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>

        {/* Ledger Section */}
        <div className="lg:col-span-12 space-y-6">
            <div className="bg-slate-900 border border-white/5 rounded-[40px] overflow-hidden shadow-sm">
                <header className="p-8 border-b border-white/5 flex flex-col sm:flex-row justify-between items-center bg-white/[0.01] gap-6">
                    <div className="flex items-center gap-3">
                        <History className="text-indigo-400" size={24} />
                        <div>
                            <h3 className="text-lg font-bold text-white tracking-tight">Transaction Ledger</h3>
                            <p className="text-xs font-medium text-slate-600">Complete statement of accounts</p>
                        </div>
                    </div>
                    
                    <div className="flex items-center gap-3">
                        <div className="bg-slate-800 border border-white/5 p-2 px-4 rounded-xl flex items-center gap-3">
                            <Search size={16} className="text-slate-500" />
                            <input type="text" placeholder="Search reference..." className="bg-transparent border-none outline-none text-xs text-slate-300 w-40" />
                        </div>
                        <button className="p-3 bg-white/5 hover:bg-white/10 rounded-xl text-slate-500 border border-white/5 transition-all">
                            <Filter size={18} />
                        </button>
                    </div>
                </header>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                         <thead>
                            <tr className="bg-white/[0.02] text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-white/5">
                                <th className="p-6">Method / Asset</th>
                                <th className="p-6">Amount (₦)</th>
                                <th className="p-6">Transaction ID</th>
                                <th className="p-6">Verification</th>
                                <th className="p-6 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-white/5">
                            {loading ? (
                                <tr>
                                    <td colSpan={5} className="p-20 text-center text-xs font-bold text-slate-700 animate-pulse">Syncing ledger records...</td>
                                </tr>
                            ) : transactions.map(tx => (
                                <tr key={tx.id} className="hover:bg-white/[0.02] transition-colors group">
                                    <td className="p-6">
                                        <div className="flex items-center gap-3">
                                            <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${tx.type === 'credit' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500'}`}>
                                                {tx.type === 'credit' ? <ArrowDownLeft size={20} /> : <ArrowUpRight size={20} />}
                                            </div>
                                            <div>
                                                 <p className="text-sm font-bold text-white capitalize">{tx.source.replace('_', ' ')}</p>
                                                <p className="text-xs font-bold text-slate-600 uppercase tracking-tight">{tx.type}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="p-6">
                                        <p className={`text-sm font-bold tracking-tight ${tx.type === 'credit' ? 'text-emerald-500' : 'text-slate-200'}`}>
                                            {tx.type === 'credit' ? '+' : '-'} {Number(tx.amount).toLocaleString()}
                                        </p>
                                    </td>
                                    <td className="p-6">
                                         <p className="text-xs font-mono font-bold text-slate-600 group-hover:text-slate-400 transition-colors">
                                            {tx.reference}
                                        </p>
                                    </td>
                                    <td className="p-6">
                                         <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider ${tx.status === 'success' || tx.status === 'completed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'}`}>
                                            <div className={`w-1 h-1 rounded-full ${tx.status === 'success' || tx.status === 'completed' ? 'bg-emerald-500' : 'bg-rose-500'}`}></div>
                                            {tx.status}
                                        </span>
                                    </td>
                                    <td className="p-6 text-right">
                                        <p className="text-xs font-bold text-slate-500 group-hover:text-slate-400 transition-colors">
                                            {new Date(tx.created_at).toLocaleDateString()}
                                        </p>
                                         <p className="text-[11px] font-bold text-slate-700">
                                            {new Date(tx.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                        </p>
                                    </td>
                                </tr>
                            ))}
                            {!loading && transactions.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="p-20 text-center opacity-20 flex flex-col items-center gap-4">
                                        <History size={48} className="text-slate-500" />
                                         <p className="text-sm font-bold uppercase tracking-wider text-slate-500">No Ledger Logs Detected</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default Wallet;
