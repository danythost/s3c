import React from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { 
  Database, 
  Smartphone, 
  ShoppingBag,
  User as UserIcon, 
  LogOut, 
  ChevronRight,
  Zap,
  Menu,
  X
} from 'lucide-react';

interface WebsiteLayoutProps {
  children: React.ReactNode;
  darkMode?: boolean;
}

const WebsiteLayout: React.FC<WebsiteLayoutProps> = ({ children, darkMode = false }) => {
  const { user, logout, loading } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [mobileMenuOpen, setMobileMenuOpen] = React.useState(false);

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  const navLinks = [
    { name: 'Services', path: '/#services' },
    { name: 'Airtime', path: '/purchase/airtime', icon: <Smartphone size={16} /> },
    { name: 'Data', path: '/purchase/data', icon: <Database size={16} /> },
    { name: 'Shop', path: '/shop', icon: <ShoppingBag size={16} /> },
  ];

  const isActive = (path: string) => location.pathname === path;

  return (
    <div className={`min-h-screen font-sans selection:bg-indigo-100 selection:text-indigo-900 ${darkMode ? 'bg-[#0F172A]' : 'bg-slate-50'}`}>
      {/* Navigation */}
      <nav className={`sticky top-0 z-50 backdrop-blur-2xl border-b transition-colors duration-300 pt-safe ${darkMode ? 'bg-[#0F172A]/80 border-white/5' : 'bg-white/80 border-slate-100'}`}>
        <div className="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">
          <div className="flex items-center gap-12">
            <Link to="/" className={`text-2xl font-bold italic tracking-tight flex items-center gap-3 group ${darkMode ? 'text-white' : 'text-slate-900'}`}>
              <span className="bg-indigo-600 text-white w-10 h-10 rounded-xl flex items-center justify-center not-italic shadow-lg shadow-indigo-600/20 group-hover:rotate-12 transition-transform">S</span>
              3C<span className="text-indigo-600 not-italic">.</span>
            </Link>

            <div className="hidden md:flex items-center gap-8">
              {navLinks.map(link => (
                <Link 
                  key={link.path}
                  to={link.path} 
                  className={`text-[11px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 ${isActive(link.path) ? 'text-indigo-600' : darkMode ? 'text-slate-500 hover:text-white' : 'text-slate-400 hover:text-slate-900'}`}
                >
                  {link.icon}
                  {link.name}
                </Link>
              ))}
            </div>
          </div>

          <div className="flex items-center gap-4">
            {!loading && (
              user ? (
                <div className="flex items-center gap-4">
                  {/* Wallet Widget */}
                  <div className={`hidden sm:flex flex-col items-end px-6 border-r ${darkMode ? 'border-white/5' : 'border-slate-100'}`}>
                    <span className="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Active Balance</span>
                    <span className={`text-lg font-bold tabular-nums ${darkMode ? 'text-white' : 'text-slate-900'}`}>₦{Number(user.wallet_balance || 0).toLocaleString()}</span>
                  </div>
                  
                  <div className={`flex items-center gap-3 p-1.5 rounded-2xl group cursor-pointer transition-all ${darkMode ? 'bg-white/5 hover:bg-white/10' : 'bg-slate-100 hover:bg-slate-200'}`}>
                    <div className={`w-10 h-10 rounded-xl flex items-center justify-center shadow-sm ${darkMode ? 'bg-slate-900 text-slate-400' : 'bg-white text-slate-600'}`}>
                       <UserIcon size={20} />
                    </div>
                    <div className="hidden lg:block pr-4">
                        <p className={`text-[10px] font-bold uppercase tracking-wider leading-none mb-1 ${darkMode ? 'text-slate-500' : 'text-slate-400'}`}>Account</p>
                        <p className={`text-xs font-bold leading-none truncate max-w-[100px] ${darkMode ? 'text-white' : 'text-slate-900'}`}>{user.name}</p>
                    </div>
                    <button 
                      onClick={handleLogout}
                      className="p-3 text-slate-400 hover:text-rose-500 transition-colors"
                      title="Logout"
                    >
                      <LogOut size={18} />
                    </button>
                  </div>
                </div>
              ) : (
                <div className="flex items-center gap-4">
                  <Link to="/login" className={`text-[11px] font-black uppercase tracking-widest px-4 ${darkMode ? 'text-slate-500 hover:text-white' : 'text-slate-400 hover:text-slate-900'}`}>Login</Link>
                  <Link to="/register" className={`px-8 py-4 rounded-2xl text-[11px] font-bold uppercase tracking-wider transition-all ${darkMode ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/20' : 'bg-slate-900 text-white hover:bg-indigo-600 hover:shadow-2xl hover:shadow-indigo-600/30'}`}>
                    Register
                  </Link>
                </div>
              )
            )}
            
            {/* Mobile Toggle */}
            <button 
                className={`md:hidden p-3 rounded-xl transition-colors ${darkMode ? 'bg-white/5 text-slate-400' : 'bg-slate-100 text-slate-600'}`}
                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            >
                {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
            </button>
          </div>
        </div>

        {/* Mobile Menu */}
        {mobileMenuOpen && (
            <div className={`md:hidden border-b animate-in slide-in-from-top duration-300 ${darkMode ? 'bg-[#0F172A] border-white/5' : 'bg-white border-slate-100'}`}>
                <div className="p-6 space-y-4">
                    {user && (
                        <div className={`p-6 rounded-3xl mb-6 ${darkMode ? 'bg-white/5' : 'bg-indigo-50'}`}>
                            <p className={`text-[10px] font-bold uppercase tracking-wider mb-2 ${darkMode ? 'text-slate-500' : 'text-indigo-400'}`}>My Balance</p>
                            <p className={`text-3xl font-bold ${darkMode ? 'text-white' : 'text-indigo-900'}`}>₦{Number(user.wallet_balance || 0).toLocaleString()}</p>
                        </div>
                    )}
                    {navLinks.map(link => (
                        <Link 
                            key={link.path}
                            to={link.path} 
                            onClick={() => setMobileMenuOpen(false)}
                            className={`flex items-center justify-between p-4 rounded-2xl font-black text-sm uppercase tracking-tight ${darkMode ? 'bg-white/5 text-white' : 'bg-slate-50 text-slate-900'}`}
                        >
                            <span className="flex items-center gap-3">{link.icon} {link.name}</span>
                            <ChevronRight size={18} className={darkMode ? 'text-slate-700' : 'text-slate-300'} />
                        </Link>
                    ))}
                    {!user && (
                        <div className="grid grid-cols-2 gap-4 pt-4">
                            <Link to="/login" className={`p-4 rounded-2xl text-center font-black text-xs uppercase tracking-widest ${darkMode ? 'bg-white/5 text-slate-400' : 'bg-slate-100 text-slate-600'}`}>Login</Link>
                            <Link to="/register" className={`p-4 rounded-2xl text-center font-black text-xs uppercase tracking-widest text-white shadow-lg ${darkMode ? 'bg-indigo-600 shadow-indigo-600/20' : 'bg-indigo-600 shadow-indigo-100'}`}>Register</Link>
                        </div>
                    )}
                </div>
            </div>
        )}
      </nav>

      <main>{children}</main>

      {/* Modern Footer */}
      <footer className={`border-t py-20 px-6 ${darkMode ? 'bg-[#0F172A] border-white/5' : 'bg-white border-slate-100'}`}>
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div className="space-y-6">
              <Link to="/" className={`text-2xl font-bold italic tracking-tight flex items-center gap-2 ${darkMode ? 'text-white' : 'text-slate-900'}`}>
                S3C<span className="text-indigo-600 not-italic">.</span>
              </Link>
              <p className={`text-sm leading-relaxed font-medium ${darkMode ? 'text-slate-400' : 'text-slate-400'}`}>
                Premium digital infrastructure and VTU services. Empowering the next generation of commerce.
              </p>
            </div>
            <div>
              <h4 className={`font-bold text-[10px] uppercase tracking-widest mb-8 ${darkMode ? 'text-slate-300' : 'text-slate-900'}`}>Services</h4>
              <ul className="space-y-4">
                <li><Link to="/purchase/data" className={`text-xs font-bold transition-colors ${darkMode ? 'text-slate-500 hover:text-white' : 'text-slate-400 hover:text-indigo-600'}`}>Buy Data</Link></li>
                <li><Link to="/purchase/airtime" className={`text-xs font-bold transition-colors ${darkMode ? 'text-slate-500 hover:text-white' : 'text-slate-400 hover:text-indigo-600'}`}>Buy Airtime</Link></li>
                <li><Link to="/shop" className={`text-xs font-bold transition-colors ${darkMode ? 'text-slate-500 hover:text-white' : 'text-slate-400 hover:text-indigo-600'}`}>Marketplace</Link></li>
              </ul>
            </div>
            <div>
              <h4 className={`font-bold text-[10px] uppercase tracking-widest mb-8 ${darkMode ? 'text-slate-300' : 'text-slate-900'}`}>Information</h4>
              <ul className="space-y-4">
                <li><Link to="#" className={`${darkMode ? 'text-slate-500' : 'text-slate-400'} font-bold text-xs`}>Privacy Policy</Link></li>
                <li><Link to="#" className={`${darkMode ? 'text-slate-500' : 'text-slate-400'} font-bold text-xs`}>Terms of Service</Link></li>
                <li><Link to="#" className={`${darkMode ? 'text-slate-500' : 'text-slate-400'} font-bold text-xs`}>Help Center</Link></li>
              </ul>
            </div>
            <div>
              <h4 className={`font-bold text-[10px] uppercase tracking-widest mb-8 ${darkMode ? 'text-slate-300' : 'text-slate-900'}`}>Newsletter</h4>
              <div className="relative group">
                <input 
                  type="email" 
                  placeholder="Enter Your Email"
                  className={`w-full border p-5 rounded-2xl outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-xs ${darkMode ? 'bg-white/5 border-white/5 text-white placeholder:text-slate-700' : 'bg-slate-50 border-slate-100 placeholder:text-slate-400'}`}
                />
                <button className={`absolute right-2 top-2 p-3 text-white rounded-xl transition-all ${darkMode ? 'bg-indigo-600 hover:bg-indigo-500' : 'bg-slate-900 hover:bg-indigo-600'}`}>
                  <Zap size={14} />
                </button>
              </div>
            </div>
          </div>
          <div className={`mt-20 pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-6 ${darkMode ? 'border-white/5' : 'border-slate-50'}`}>
            <p className={`text-[9px] font-bold uppercase tracking-widest ${darkMode ? 'text-slate-500' : 'text-slate-300'}`}>© 2026 S3C DIGITAL. ALL RIGHTS RESERVED.</p>
            <div className="flex gap-8">
               <span className="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
               <span className={`text-[9px] font-black uppercase tracking-widest ${darkMode ? 'text-slate-600' : 'text-slate-300'}`}>All Systems Online</span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default WebsiteLayout;
