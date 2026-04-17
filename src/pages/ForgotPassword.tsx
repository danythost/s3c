import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import client from '../api/client';
import { Zap, Mail, AlertCircle, ArrowRight, CheckCircle2 } from 'lucide-react';

export const ForgotPassword: React.FC = () => {
  const [email, setEmail] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      await client.post('/password/email', { email });
      setSuccess(true);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to send reset link. Please try again.');
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
          <h2 className="text-xl font-bold text-white tracking-tight">Recover your account</h2>
          <p className="text-slate-500 text-sm mt-2">Enter your email and we'll send you a reset link.</p>
        </div>

        <div className="bg-slate-900 border border-white/5 rounded-3xl p-8 lg:p-10 shadow-2xl">
          {success ? (
            <div className="text-center space-y-6">
              <div className="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto border border-emerald-500/20">
                <CheckCircle2 size={40} className="text-emerald-500" />
              </div>
              <div className="space-y-2">
                <h3 className="text-xl font-bold text-white">Check your email</h3>
                <p className="text-slate-400 text-sm">We've sent a password reset link to <span className="text-indigo-400 font-semibold">{email}</span></p>
              </div>
              <Link
                to="/login"
                className="w-full bg-slate-800 hover:bg-slate-700 text-white font-bold py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 mt-4"
              >
                Back to Login
              </Link>
            </div>
          ) : (
            <>
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

                <button
                  type="submit"
                  disabled={loading}
                  className="w-full bg-indigo-600 hover:bg-white hover:text-slate-900 text-white font-bold py-4 rounded-2xl shadow-xl transition-all flex items-center justify-center gap-3 disabled:opacity-50 group mt-4 active:scale-95"
                >
                  {loading ? 'Sending link...' : 'Send Reset Link'}
                  <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
                </button>
              </form>

              <div className="mt-8 text-center pt-8 border-t border-white/5">
                <p className="text-slate-500 text-sm font-medium">
                  Remembered your password?{' '}
                  <Link to="/login" className="text-indigo-400 hover:text-indigo-300 font-bold underline underline-offset-4">Sign In</Link>
                </p>
              </div>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default ForgotPassword;
