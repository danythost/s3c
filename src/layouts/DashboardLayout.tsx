import React, { useState, useEffect, useRef } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import client from '../api/client';
import { 
  Home, 
  Database, 
  Smartphone, 
  History, 
  User as UserIcon, 
  LogOut, 
  Menu, 
  Zap,
  Bell,
  Search,
  ChevronRight,
  ArrowRight,
  Info,
  AlertTriangle,
  Clock
} from 'lucide-react';

interface Announcement {
  id: number;
  title: string;
  message: string;
  type: 'info' | 'warning' | 'danger' | 'success';
  created_at: string;
}

interface DashboardLayoutProps {
  children: React.ReactNode;
}

const DashboardLayout: React.FC<DashboardLayoutProps> = ({ children }) => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [isSidebarOpen, setSidebarOpen] = useState(false);
  const [isNotificationsOpen, setNotificationsOpen] = useState(false);
  const [announcements, setAnnouncements] = useState<Announcement[]>([]);
  const [hasUnread, setHasUnread] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  const navLinks = [
    { name: 'Dashboard', path: '/dashboard', icon: <Home size={18} /> },
    { name: 'Buy Data', path: '/purchase/data', icon: <Database size={18} /> },
    { name: 'Buy Airtime', path: '/purchase/airtime', icon: <Smartphone size={18} /> },
    { name: 'Airtime to Cash', path: '/a2c', icon: <ArrowRight size={18} /> },
    { name: 'History', path: '/wallet', icon: <History size={18} /> },
    { name: 'Profile', path: '/profile', icon: <UserIcon size={18} /> },
  ];

  useEffect(() => {
    client.get('/public/announcements')
      .then(res => {
        const data = res.data.data || [];
        setAnnouncements(data);
        const lastSeenId = localStorage.getItem('last_seen_announcement_id');
        if (data.length > 0 && (!lastSeenId || parseInt(lastSeenId) < data[0].id)) {
          setHasUnread(true);
        }
      })
      .catch(err => console.error('Notification node failure:', err));

    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setNotificationsOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const handleToggleNotifications = () => {
    setNotificationsOpen(!isNotificationsOpen);
    if (!isNotificationsOpen && announcements.length > 0) {
      setHasUnread(false);
      localStorage.setItem('last_seen_announcement_id', announcements[0].id.toString());
    }
  };

  const handleLogout = async () => {
    await logout();
    navigate('/');
  };

  const isActive = (path: string) => location.pathname === path;

  return (
    <div className="min-h-screen bg-[#0F172A] flex overflow-hidden font-sans text-slate-200">
      {/* Sidebar - Desktop */}
      <aside className={`fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-white/5 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full'}`}>
        <div className="h-full flex flex-col p-6">
          {/* Logo */}
          <div className="flex items-center gap-3 px-2 mb-10">
            <div className="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-indigo-600/20">
              <Zap size={18} className="text-white fill-current" />
            </div>
            <h1 className="text-xl font-bold tracking-tight text-white">S3C</h1>
          </div>

          {/* Navigation */}
          <nav className="flex-1 space-y-1">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                onClick={() => setSidebarOpen(false)}
                className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group ${isActive(link.path) ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-white/5 hover:text-white'}`}
              >
                <span className={isActive(link.path) ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors'}>
                  {link.icon}
                </span>
                <span className="font-semibold text-sm tracking-tight">{link.name}</span>
                {isActive(link.path) && <ChevronRight size={14} className="ml-auto opacity-50" />}
              </Link>
            ))}
          </nav>

          {/* User Profile Card */}
          <div className="bg-white/5 rounded-2xl p-4 mt-auto border border-white/5">
            <div className="flex items-center gap-3 mb-4">
              <div className="w-9 h-9 bg-indigo-600/20 rounded-lg flex items-center justify-center text-indigo-400 font-bold text-sm">
                {user?.name?.[0].toUpperCase()}
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-bold text-sm text-white truncate leading-none mb-1">{user?.name}</p>
                <p className="text-[10px] font-medium text-emerald-400 leading-none">
                    Verified Account
                </p>
              </div>
            </div>
            <button 
              onClick={handleLogout}
              className="w-full flex items-center justify-center gap-2 py-2.5 bg-white/5 hover:bg-rose-500/10 hover:text-rose-500 transition-all rounded-lg text-xs font-semibold text-slate-400 border border-white/5"
            >
              <LogOut size={16} />
              Sign Out
            </button>
          </div>
        </div>
      </aside>

      {/* Main Content Area */}
      <div className="flex-1 flex flex-col h-screen overflow-hidden">
        {/* Top Header */}
        <header className="h-20 bg-slate-900 border-b border-white/5 flex items-center justify-between px-6 lg:px-8 shrink-0 sticky top-0 z-40">
          <div className="flex items-center gap-6">
            <button 
              onClick={() => setSidebarOpen(true)}
              className="lg:hidden p-2 bg-white/5 rounded-lg text-slate-400 border border-white/5"
            >
              <Menu size={20} />
            </button>
            <div className="hidden lg:flex items-center gap-3 bg-white/5 border border-white/5 px-4 py-2 rounded-xl w-80 focus-within:border-indigo-500/50 transition-all">
                <Search size={16} className="text-slate-500" />
                <input 
                    type="text" 
                    placeholder="Search services..." 
                    className="bg-transparent border-none outline-none text-sm placeholder:text-slate-600 w-full text-slate-300"
                />
            </div>
          </div>

          <div className="flex items-center gap-4">
            <div className="flex items-center gap-3 relative" ref={dropdownRef}>
                <button 
                  onClick={handleToggleNotifications}
                  className={`p-2.5 rounded-lg transition-colors relative border border-white/5 ${isNotificationsOpen ? 'bg-indigo-600 text-white shadow-lg' : 'bg-white/5 text-slate-400 hover:text-indigo-400'}`}
                >
                    <Bell size={20} />
                    {hasUnread && (
                      <span className="absolute top-2.5 right-2.5 w-1.5 h-1.5 bg-rose-500 rounded-full shadow-[0_0_8px_rgba(244,63,94,0.4)]"></span>
                    )}
                </button>

                {/* Notifications Dropdown */}
                {isNotificationsOpen && (
                  <div className="absolute top-14 right-0 w-80 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                    <div className="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                        <h4 className="text-xs font-bold text-slate-400">Notifications</h4>
                        <span className="text-[10px] font-bold text-slate-600 bg-white/5 px-2 py-0.5 rounded-md">Status</span>
                    </div>
                    <div className="max-h-96 overflow-y-auto">
                      {announcements.length > 0 ? (
                        <div className="divide-y divide-white/5">
                          {announcements.map((item) => (
                            <div key={item.id} className="p-4 hover:bg-white/[0.03] transition-colors group cursor-default">
                                <div className="flex gap-3">
                                  <div className="mt-1 flex-shrink-0">
                                    {item.type === 'info' ? <Info size={16} className="text-indigo-400" /> : <AlertTriangle size={16} className="text-amber-400" />}
                                  </div>
                                  <div className="space-y-1">
                                    <p className="text-sm font-bold text-slate-200 leading-tight">{item.title}</p>
                                    <p className="text-xs text-slate-500 leading-relaxed">{item.message}</p>
                                    <div className="flex items-center gap-1.5 text-[10px] font-medium text-slate-600 pt-1">
                                       <Clock size={12} />
                                       {new Date(item.created_at).toLocaleDateString()}
                                    </div>
                                  </div>
                                </div>
                            </div>
                          ))}
                        </div>
                      ) : (
                        <div className="p-10 text-center space-y-3 opacity-20">
                            <Zap size={24} className="mx-auto" />
                            <p className="text-xs font-bold">Safe and Sound</p>
                        </div>
                      )}
                    </div>
                    <Link to="/notifications" className="block p-3 border-t border-white/5 text-center text-[10px] font-bold text-slate-500 hover:text-white transition-colors">
                        View All Activities
                    </Link>
                  </div>
                )}
            </div>
          </div>
        </header>

        {/* Content View */}
        <main className="flex-1 overflow-y-auto p-6 lg:p-8">
          <div className="max-w-7xl mx-auto">
            {children}
          </div>
        </main>
      </div>

      {/* Overlay - Mobile Sidebar */}
      {isSidebarOpen && (
        <div 
          className="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"
          onClick={() => setSidebarOpen(false)}
        ></div>
      )}
    </div>
  );
};

export default DashboardLayout;
