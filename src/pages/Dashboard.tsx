import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import client from '../api/client';
import { useAuth } from '../context/AuthContext';
import DashboardLayout from '../layouts/DashboardLayout';
import { 
  ArrowUpRight, 
  Database, 
  Smartphone, 
  ChevronRight, 
  TrendingUp, 
  Clock, 
  Plus,
  ShieldCheck,
  Activity,
  RotateCw
} from 'lucide-react';

interface Stats {
  total_activity: number;
  success_rate: number;
  monthly_volume: number;
}

interface Transaction {
  id: number;
  source: string;
  type: 'credit' | 'debit';
  amount: number;
  status: string;
  created_at: string;
}

const Dashboard: React.FC = () => {
  const { user, refreshUser } = useAuth();
  const [stats, setStats] = useState<Stats | null>(null);
  const [activities, setActivities] = useState<Transaction[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    client.get('/dashboard')
      .then(res => {
        setStats(res.data.stats);
        setActivities(res.data.activities);
      })
      .catch(err => console.error('Dashboard node failure:', err))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return (
    <DashboardLayout>
        <div className="flex flex-col items-center justify-center h-[50vh] space-y-4">
             <div className="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
             <p className="text-xs font-semibold text-slate-500">Syncing Dashboard...</p>
        </div>
    </DashboardLayout>
  );

  const services = [
    { name: 'Data Bundle', icon: <Database size={18} />, path: '/purchase/data', color: 'bg-indigo-600', linkText: 'Buy Now' },
    { name: 'Airtime', icon: <Smartphone size={18} />, path: '/purchase/airtime', color: 'bg-emerald-500', linkText: 'Top Up' },
  ];

  return (
    <DashboardLayout>
      <header className="mb-8">
        <h1 className="text-2xl font-bold text-white tracking-tight">
            Welcome back, {user?.name.split(' ')[0]}
        </h1>
        <p className="text-slate-500 text-sm font-medium mt-1">Here is a summary of your account activity.</p>
      </header>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Main Column */}
        <div className="lg:col-span-8 space-y-6">
            <div className="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-3xl p-8 lg:p-10 text-white relative overflow-hidden shadow-xl">
                <div className="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div className="space-y-4 text-center md:text-left">
                        <div className="flex items-center justify-center md:justify-start gap-4">
                            <span className="text-xs font-bold text-indigo-100 uppercase tracking-wider opacity-80">Available Balance</span>
                            <button 
                                onClick={async () => {
                                    setRefreshing(true);
                                    try {
                                        await client.post('/wallet/refresh');
                                    } catch (e) {
                                        console.error('Wallet sync failed', e);
                                    }
                                    await refreshUser();
                                    setRefreshing(false);
                                }}
                                className="p-1.5 hover:bg-white/10 rounded-lg transition-all text-indigo-100/60 hover:text-white"
                                title="Refresh Balance"
                            >
                                <RotateCw size={14} className={refreshing ? 'animate-spin' : ''} />
                            </button>
                        </div>
                        <h2 className="text-4xl md:text-5xl font-bold tracking-tight">₦{Number(user?.wallet_balance || 0).toLocaleString()}</h2>
                        <div className="flex items-center gap-2 text-indigo-100/80 font-semibold text-xs py-1 px-3 bg-white/10 rounded-lg inline-flex mx-auto md:mx-0">
                            <ShieldCheck size={14} className="text-emerald-400" />
                            Secure Wallet verified
                        </div>
                    </div>
                    <Link 
                        to="/wallet" 
                        className="bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold text-sm hover:bg-slate-900 hover:text-white transition-all shadow-lg flex items-center gap-2 active:scale-95"
                    >
                        <Plus size={18} /> Add Funds
                    </Link>
                </div>
            </div>

            {/* Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-slate-800/50 p-6 lg:p-8 rounded-3xl border border-white/5 shadow-sm hover:bg-slate-800 transition-all cursor-default group">
                    <div className="w-10 h-10 bg-indigo-500/10 text-indigo-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <TrendingUp size={20} />
                    </div>
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Monthly Usage</p>
                    <p className="text-2xl font-bold text-white tracking-tight">₦{Number(stats?.monthly_volume || 0).toLocaleString()}</p>
                </div>
                <div className="bg-slate-800/50 p-6 lg:p-8 rounded-3xl border border-white/5 shadow-sm hover:bg-slate-800 transition-all cursor-default group">
                    <div className="w-10 h-10 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center mb-4 group-hover:rotate-12 transition-transform">
                        <Activity size={20} />
                    </div>
                    <p className="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Success Rate</p>
                    <p className="text-2xl font-bold text-white tracking-tight">{stats?.success_rate}%</p>
                </div>
            </div>

            {/* Services List */}
            <div className="bg-slate-800/30 p-8 lg:p-10 rounded-3xl border border-white/5 shadow-sm">
                <h3 className="text-sm font-bold text-slate-400 uppercase tracking-widest mb-8">Available Services</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {services.map(srv => (
                        <Link 
                            key={srv.name}
                            to={srv.path}
                            className="bg-slate-900 border border-white/5 p-6 rounded-2xl hover:border-indigo-500/30 hover:bg-slate-800/50 transition-all group flex items-center justify-between"
                        >
                            <div className="flex items-center gap-4">
                                <div className={`w-12 h-12 ${srv.color} text-white rounded-xl flex items-center justify-center shadow-md group-hover:-translate-y-1 transition-transform`}>
                                    {srv.icon}
                                </div>
                                <div className="space-y-0.5">
                                    <h4 className="font-bold text-sm text-white">{srv.name}</h4>
                                    <p className="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Fast Delivery</p>
                                </div>
                            </div>
                            <div className="w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 group-hover:text-indigo-400 transition-all">
                                <ChevronRight size={18} />
                            </div>
                        </Link>
                    ))}
                </div>
            </div>
        </div>

        {/* Recent Activity Sidebar */}
        <div className="lg:col-span-4 h-full">
            <div className="bg-slate-800/30 p-8 lg:p-10 rounded-3xl border border-white/5 shadow-sm flex flex-col h-full">
                 <header className="flex justify-between items-center mb-8">
                     <div className="flex items-center gap-2">
                        <Clock className="text-indigo-400" size={18} />
                        <h3 className="text-sm font-bold text-slate-400 uppercase tracking-widest">Recent Activity</h3>
                     </div>
                     <Link to="/wallet" className="text-indigo-400 hover:text-indigo-300 transition-colors">
                        <ArrowUpRight size={16} />
                     </Link>
                 </header>

                 <div className="flex-1 space-y-3">
                     {activities.map(activity => (
                         <div key={activity.id} className="flex items-center justify-between p-4 bg-slate-900 border border-white/5 rounded-2xl hover:border-white/10 transition-colors">
                             <div className="flex items-center gap-3">
                                 <div className={`w-10 h-10 rounded-lg flex items-center justify-center font-bold text-base ${activity.type === 'credit' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'}`}>
                                    {activity.source === 'wallet_funding' ? '💰' : activity.source === 'data' ? '📶' : '📞'}
                                 </div>
                                 <div className="space-y-0.5">
                                     <p className="text-xs font-bold text-slate-200 capitalize">
                                        {activity.source.replace('_', ' ')}
                                     </p>
                                     <p className="text-[10px] font-medium text-slate-600">
                                        {new Date(activity.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                     </p>
                                 </div>
                             </div>
                             <div className="text-right">
                                 <p className={`text-sm font-bold tracking-tight ${activity.type === 'credit' ? 'text-emerald-400' : 'text-slate-200'}`}>
                                    {activity.type === 'credit' ? '+' : '-'} ₦{Number(activity.amount).toLocaleString()}
                                 </p>
                                 <span className={`text-[9px] font-bold uppercase tracking-wider opacity-60 ${activity.status === 'success' || activity.status === 'completed' ? 'text-emerald-400' : 'text-rose-400'}`}>
                                    {activity.status}
                                 </span>
                             </div>
                         </div>
                     ))}
                     {activities.length === 0 && (
                        <div className="text-center py-20 opacity-20 text-slate-500">
                            <p className="text-xs font-bold uppercase tracking-widest">No Recent Activity</p>
                        </div>
                     )}
                 </div>

                 <Link 
                    to="/wallet" 
                    className="mt-10 w-full py-4 bg-white/5 hover:bg-slate-900 border border-white/5 text-center font-bold text-xs text-slate-400 hover:text-white rounded-xl transition-all"
                 >
                    View All History
                 </Link>
            </div>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default Dashboard;
