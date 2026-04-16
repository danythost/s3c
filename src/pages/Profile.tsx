import React, { useState } from 'react';
import client from '../api/client';
import { useAuth } from '../context/AuthContext';
import DashboardLayout from '../layouts/DashboardLayout';
import { 
  User as UserIcon, 
  Shield, 
  Lock, 
  CheckCircle2, 
  AlertCircle, 
  Save,
  Key
} from 'lucide-react';

const Profile: React.FC = () => {
  const { user, refreshUser } = useAuth();
  
  // Profile Form
  const [name, setName] = useState(user?.name || '');
  const [email] = useState(user?.email || '');
  
  // Password Form
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  
  const [profileStatus, setProfileStatus] = useState<{ success: boolean; message: string } | null>(null);
  const [passwordStatus, setPasswordStatus] = useState<{ success: boolean; message: string } | null>(null);
  const [loading, setLoading] = useState(false);

  const handleUpdateProfile = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setProfileStatus(null);
    try {
      await client.post('/profile/update', { name, email });
      setProfileStatus({ success: true, message: 'Profile details updated successfully.' });
      await refreshUser();
    } catch (err: any) {
      setProfileStatus({ success: false, message: err.response?.data?.message || 'Update failed.' });
    } finally {
      setLoading(false);
    }
  };

  const handleUpdatePassword = async (e: React.FormEvent) => {
    e.preventDefault();
    if (newPassword !== confirmPassword) {
      setPasswordStatus({ success: false, message: 'New passwords do not match.' });
      return;
    }
    setLoading(true);
    setPasswordStatus(null);
    try {
      await client.post('/password/update', { 
        current_password: currentPassword, 
        password: newPassword,
        password_confirmation: confirmPassword
      });
      setPasswordStatus({ success: true, message: 'Password updated successfully.' });
      setCurrentPassword('');
      setNewPassword('');
      setConfirmPassword('');
    } catch (err: any) {
      setPasswordStatus({ success: false, message: err.response?.data?.message || 'Security update failed.' });
    } finally {
      setLoading(false);
    }
  };

  return (
    <DashboardLayout>
      <header className="mb-10">
        <h1 className="text-2xl font-bold text-white tracking-tight">User Profile</h1>
        <p className="text-slate-500 text-sm mt-1">Manage your identity and security settings.</p>
      </header>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Left Column: Profile Update */}
        <div className="lg:col-span-7 space-y-6">
            <div className="bg-slate-800/20 p-8 lg:p-10 rounded-3xl border border-white/5 shadow-sm">
                <header className="flex items-center gap-4 mb-10">
                    <div className="w-10 h-10 bg-indigo-600/10 text-indigo-400 rounded-xl flex items-center justify-center border border-white/5">
                        <UserIcon size={20} />
                    </div>
                    <div>
                        <h2 className="text-base font-bold text-white tracking-tight">Personal Profile</h2>
                        <p className="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-1">Main User Identity</p>
                    </div>
                </header>

                <form onSubmit={handleUpdateProfile} className="space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-2">
                           <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-2 group">Your Name</label>
                           <input 
                                type="text"
                                className="w-full bg-slate-900 border border-white/5 rounded-2xl p-4 text-sm font-bold text-slate-200 outline-none focus:border-indigo-500/50 transition-all shadow-inner"
                                value={name}
                                onChange={(e) => setName(e.target.value)}
                                required
                           />
                        </div>
                        <div className="space-y-2">
                           <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-2">Email Address</label>
                           <div className="relative">
                               <input 
                                    type="email"
                                    className="w-full bg-slate-900 border border-white/5 rounded-2xl p-4 text-sm font-bold text-slate-500 outline-none cursor-not-allowed opacity-60"
                                    value={email}
                                    disabled
                               />
                               <div className="absolute right-4 top-1/2 -translate-y-1/2">
                                  <Lock size={14} className="text-slate-700" />
                               </div>
                           </div>
                        </div>
                    </div>

                    {profileStatus && (
                        <div className={`p-4 rounded-xl flex items-center gap-3 border text-xs font-bold animate-in fade-in duration-300 ${profileStatus.success ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'}`}>
                            {profileStatus.success ? <CheckCircle2 size={16} /> : <AlertCircle size={16} />}
                            {profileStatus.message}
                        </div>
                    )}

                    <button 
                        type="submit"
                        disabled={loading}
                        className="w-full md:w-auto px-10 py-4 bg-indigo-600 hover:bg-white hover:text-slate-900 text-white rounded-xl font-bold text-sm transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2"
                    >
                        <Save size={16} />
                        Save Profile
                    </button>
                </form>
            </div>

            <div className="bg-slate-800/20 p-8 lg:p-10 rounded-3xl border border-white/5 shadow-sm">
                <header className="flex items-center gap-4 mb-10">
                    <div className="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center border border-white/5">
                        <Key size={20} />
                    </div>
                    <div>
                        <h2 className="text-base font-bold text-white tracking-tight">Account Security</h2>
                        <p className="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-1">Credentials Access</p>
                    </div>
                </header>

                <form onSubmit={handleUpdatePassword} className="space-y-6">
                    <div className="space-y-2">
                       <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-2">Current Password</label>
                       <input 
                            type="password"
                            className="w-full bg-slate-900 border border-white/5 rounded-2xl p-4 text-sm font-bold text-slate-200 outline-none focus:border-rose-500/30 transition-all shadow-inner"
                            value={currentPassword}
                            onChange={(e) => setCurrentPassword(e.target.value)}
                            required
                       />
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-2">
                           <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-2">New Password</label>
                           <input 
                                type="password"
                                className="w-full bg-slate-900 border border-white/5 rounded-2xl p-4 text-sm font-bold text-slate-200 outline-none focus:border-indigo-500/30 transition-all font-mono shadow-inner"
                                value={newPassword}
                                onChange={(e) => setNewPassword(e.target.value)}
                                required
                           />
                        </div>
                        <div className="space-y-2">
                           <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-2">Confirm New Password</label>
                           <input 
                                type="password"
                                className="w-full bg-slate-900 border border-white/5 rounded-2xl p-4 text-sm font-bold text-slate-200 outline-none focus:border-indigo-500/30 transition-all font-mono shadow-inner"
                                value={confirmPassword}
                                onChange={(e) => setConfirmPassword(e.target.value)}
                                required
                           />
                        </div>
                    </div>

                    {passwordStatus && (
                        <div className={`p-4 rounded-xl flex items-center gap-3 border text-xs font-bold animate-in fade-in duration-300 ${passwordStatus.success ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 border-rose-500/20 text-rose-400'}`}>
                            {passwordStatus.success ? <CheckCircle2 size={16} /> : <AlertCircle size={16} />}
                            {passwordStatus.message}
                        </div>
                    )}

                    <button 
                        type="submit"
                        disabled={loading}
                        className="w-full md:w-auto px-10 py-4 bg-slate-900 hover:bg-rose-600 text-white rounded-xl font-bold text-sm transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2 border border-white/5"
                    >
                        <Shield size={16} />
                        Update Password
                    </button>
                </form>
            </div>
        </div>

        {/* Right Column: Account Status */}
        <div className="lg:col-span-5 space-y-6">
            <div className="bg-slate-900 p-8 lg:p-10 rounded-[32px] border border-white/5 shadow-2xl relative overflow-hidden">
                <div className="absolute top-0 right-0 w-64 h-64 bg-indigo-600/5 blur-[80px] rounded-full -mr-32 -mt-32"></div>
                <div className="relative z-10 space-y-8">
                    <header>
                        <p className="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-1 leading-none">Security Access</p>
                        <h3 className="text-2xl font-bold text-white tracking-tight">Account Status</h3>
                    </header>

                    <div className="space-y-4">
                        {[
                            { l: 'Network Username', v: user?.username || '@anonymous' },
                            { l: 'Registry ID', v: `S3C-${user?.id.toString().padStart(4, '0')}` },
                            { l: 'Account Role', v: user?.role.toUpperCase() },
                            { l: 'Current Balance', v: `₦${Number(user?.wallet_balance || 0).toLocaleString()}` }
                        ].map(item => (
                            <div key={item.l} className="flex justify-between items-center py-4 border-b border-white/5 last:border-0 group cursor-default">
                                <span className="text-xs font-bold text-slate-500 uppercase tracking-wider">{item.l}</span>
                                <span className="text-xs font-bold text-slate-200 group-hover:text-indigo-400 transition-colors">{item.v}</span>
                            </div>
                        ))}
                    </div>

                    <div className="p-4 bg-emerald-500/5 rounded-2xl border border-emerald-500/10 text-[10px] font-bold text-emerald-500/70 uppercase tracking-wider text-center leading-relaxed">
                        Security Status: Level 1 <br />
                        No account issues detected.
                    </div>
                </div>
            </div>

            <div className="bg-white/5 border border-white/5 p-8 rounded-[32px] flex items-center gap-5">
                <div className="w-12 h-12 bg-indigo-600/20 text-indigo-400 rounded-xl flex items-center justify-center shadow-lg">
                    <CheckCircle2 size={24} />
                </div>
                <div>
                    <h4 className="text-xs font-bold text-white uppercase tracking-wider mb-1">Account Verified</h4>
                    <p className="text-[10px] font-medium text-slate-500 leading-tight">Your account identity is fully registered and active.</p>
                </div>
            </div>
        </div>
      </div>
    </DashboardLayout>
  );
};

export default Profile;
