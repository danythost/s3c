import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import client from '../api/client';
import { Zap, Mail, Lock, User, Eye, EyeOff, AlertCircle, ArrowRight } from 'lucide-react';

export const Login: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      const response = await client.post('/login', { email, password, device_name: 'website' });
      const data = response.data;
      if (data.token || data.access_token) {
        login(data.token || data.access_token, data.user);
        navigate('/dashboard');
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Invalid credentials provided.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#0F172A] flex items-center justify-center p-6 relative overflow-hidden">
      {/* Abstract Background Accents */}
      <div className="absolute top-0 right-0 w-96 h-96 bg-indigo-600/10 blur-[100px] rounded-full -mr-48 -mt-48 animate-pulse"></div>
      <div className="absolute bottom-0 left-0 w-80 h-80 bg-emerald-500/5 blur-[100px] rounded-full -ml-40 -mb-40"></div>

      <div className="w-full max-w-md relative z-10">
        <div className="text-center mb-10">
          <Link to="/" className="inline-flex items-center gap-3 mb-6 group">
            <div className="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-600/20 group-hover:scale-110 transition-transform">
              <Zap size={24} className="text-white fill-current" />
            </div>
            <h1 className="text-3xl font-bold text-white tracking-tighter">S3C.</h1>
          </Link>
          <h2 className="text-xl font-bold text-white tracking-tight">Sign in to your account</h2>
          <p className="text-slate-500 text-sm mt-2">Welcome back! Please enter your details below.</p>
        </div>

        <div className="bg-slate-900 border border-white/5 rounded-3xl p-8 lg:p-10 shadow-2xl">
          {error && (
            <div className="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center gap-3 text-rose-500 text-sm font-semibold">
              <AlertCircle size={18} />
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="space-y-2">
              <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Email Address</label>
              <div className="relative group">
                <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                <input
                  type="email"
                  className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/5 transition-all"
                  placeholder="name@example.com"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>
            </div>

            <div className="space-y-2">
              <div className="flex justify-between px-1">
                <label className="text-xs font-bold text-slate-400 uppercase tracking-wider font-semibold">Password</label>
                <Link to="/forgot-password" className="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">Forgot Key?</Link>
              </div>
              <div className="relative group">
                <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                <input
                  type={showPassword ? 'text' : 'password'}
                  className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/5 transition-all"
                  placeholder="••••••••"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300"
                >
                  {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                </button>
              </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full bg-indigo-600 hover:bg-white hover:text-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 disabled:opacity-50 group mt-4 active:scale-95"
            >
              {loading ? 'Authenticating...' : 'Sign In'}
              <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
            </button>
          </form>

          <div className="mt-8 text-center pt-8 border-t border-white/5">
            <p className="text-slate-500 text-sm font-medium">
              Don't have an account?{' '}
              <Link to="/register" className="text-indigo-400 hover:text-indigo-300 font-bold underline underline-offset-4">Sign Up</Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export const Register: React.FC = () => {
  const [formData, setFormData] = useState({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      const response = await client.post('/register', { ...formData, device_name: 'website' });
      const data = response.data;
      if (data.token || data.access_token) {
        login(data.token || data.access_token, data.user);
        navigate('/dashboard');
      }
    } catch (err: any) {
      setError(err.response?.data?.message || 'Validation failed. Check your network nodes.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#0F172A] flex items-center justify-center p-6 relative overflow-hidden">
      {/* Accents */}
      <div className="absolute top-0 left-0 w-96 h-96 bg-indigo-600/5 blur-[100px] rounded-full -ml-48 -mt-48 transition-all"></div>
      
      <div className="w-full max-w-xl relative z-10">
        <div className="text-center mb-10">
          <Link to="/" className="inline-flex items-center gap-3 mb-6">
            <div className="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
              <Zap size={24} className="text-white fill-current" />
            </div>
            <h1 className="text-3xl font-bold text-white tracking-tighter">S3C.</h1>
          </Link>
          <h2 className="text-xl font-bold text-white tracking-tight">Create your account</h2>
          <p className="text-slate-500 text-sm mt-2">Join S3C and start managing your assets.</p>
        </div>

        <div className="bg-slate-900 border border-white/5 rounded-[32px] p-8 lg:p-12 shadow-2xl">
          {error && (
            <div className="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center gap-3 text-rose-500 text-sm font-semibold italic uppercase tracking-tight">
              <AlertCircle size={18} />
              {error}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Full Name</label>
                    <div className="relative group">
                        <User className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                        <input
                        type="text"
                        className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 transition-all font-semibold"
                        placeholder="John Doe"
                        value={formData.name}
                        onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                        required
                        />
                    </div>
                </div>
                <div className="space-y-2">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Username</label>
                    <div className="relative group">
                        <User className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                        <input
                        type="text"
                        className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 transition-all font-semibold"
                        placeholder="johndoe"
                        value={formData.username}
                        onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                        required
                        />
                    </div>
                </div>
            </div>

            <div className="space-y-2">
              <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Email Address</label>
              <div className="relative group">
                <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                <input
                  type="email"
                  className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 transition-all font-semibold"
                  placeholder="name@example.com"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  required
                />
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Password</label>
                    <div className="relative group">
                        <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                        <input
                        type="password"
                        className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 transition-all font-semibold"
                        placeholder="••••••••"
                        value={formData.password}
                        onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                        required
                        />
                    </div>
                </div>
                <div className="space-y-2">
                    <label className="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">Confirm Password</label>
                    <div className="relative group">
                        <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-indigo-500 transition-colors" size={18} />
                        <input
                        type="password"
                        className="w-full bg-slate-800/50 border border-white/10 rounded-2xl p-4 pl-12 text-white outline-none focus:border-indigo-500/50 transition-all font-semibold"
                        placeholder="••••••••"
                        value={formData.password_confirmation}
                        onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                        required
                        />
                    </div>
                </div>
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full bg-indigo-600 hover:bg-white hover:text-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 group mt-4 active:scale-95 disabled:opacity-50"
            >
              {loading ? 'Registering Account...' : 'Sign Up'}
              <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
            </button>
          </form>

          <div className="mt-8 text-center pt-8 border-t border-white/5">
            <p className="text-slate-500 text-sm font-medium">
              Already have an account?{' '}
              <Link to="/login" className="text-indigo-400 hover:text-indigo-300 font-bold underline underline-offset-4">Sign In</Link>
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};
