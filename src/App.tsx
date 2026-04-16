import React from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import { Login, Register } from './pages/Auth';
import LandingPage from './pages/LandingPage';
import Shop from './pages/Shop';
import Dashboard from './pages/Dashboard';
import DataPurchase from './pages/DataPurchase';
import AirtimePurchase from './pages/AirtimePurchase';
import Wallet from './pages/Wallet';
import Profile from './pages/Profile';
import A2C from './pages/A2C';

const ProtectedRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { user, loading } = useAuth();
  
  if (loading) return (
    <div className="flex justify-center items-center h-screen bg-slate-900">
      <div className="text-indigo-500 font-black animate-pulse italic text-2xl tracking-tighter">
        IDENTIFYING NODE...
      </div>
    </div>
  );
  
  if (!user) return <Navigate to="/login" replace />;
  
  return <>{children}</>;
};

const AuthRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { user, loading } = useAuth();
  if (loading) return <div className="flex justify-center items-center h-screen">Loading...</div>;
  if (user) return <Navigate to="/dashboard" replace />;
  return <>{children}</>;
};

const AppRoutes: React.FC = () => {
  return (
    <Routes>
      <Route path="/" element={<LandingPage />} />
      <Route path="/shop" element={<Shop />} />
      <Route path="/login" element={<AuthRoute><Login /></AuthRoute>} />
      <Route path="/register" element={<AuthRoute><Register /></AuthRoute>} />
      
      {/* Dashboard & Services */}
      <Route path="/dashboard" element={<ProtectedRoute><Dashboard /></ProtectedRoute>} />
      <Route path="/wallet" element={<ProtectedRoute><Wallet /></ProtectedRoute>} />
      <Route path="/a2c" element={<ProtectedRoute><A2C /></ProtectedRoute>} />
      <Route path="/profile" element={<ProtectedRoute><Profile /></ProtectedRoute>} />
      <Route path="/purchase/data" element={<ProtectedRoute><DataPurchase /></ProtectedRoute>} />
      <Route path="/purchase/airtime" element={<ProtectedRoute><AirtimePurchase /></ProtectedRoute>} />
      
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

const App: React.FC = () => {
  return (
    <BrowserRouter>
      <AuthProvider>
        <AppRoutes />
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;
