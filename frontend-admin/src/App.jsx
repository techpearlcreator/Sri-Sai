import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import ProtectedRoute from './components/ProtectedRoute';
import AdminLayout from './layouts/AdminLayout';
import LoginPage from './pages/auth/LoginPage';
import DashboardPage from './pages/dashboard/DashboardPage';
import PlaceholderPage from './pages/PlaceholderPage';
import './App.css';

function LoginRoute() {
  const { user, loading } = useAuth();
  if (loading) return null;
  if (user) return <Navigate to="/" replace />;
  return <LoginPage />;
}

function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Toaster position="top-right" toastOptions={{ duration: 3000 }} />
        <Routes>
          <Route path="/login" element={<LoginRoute />} />
          <Route
            element={
              <ProtectedRoute>
                <AdminLayout />
              </ProtectedRoute>
            }
          >
            <Route index element={<DashboardPage />} />
            <Route path="blogs" element={<PlaceholderPage />} />
            <Route path="magazines" element={<PlaceholderPage />} />
            <Route path="gallery" element={<PlaceholderPage />} />
            <Route path="events" element={<PlaceholderPage />} />
            <Route path="pages" element={<PlaceholderPage />} />
            <Route path="trustees" element={<PlaceholderPage />} />
            <Route path="donations" element={<PlaceholderPage />} />
            <Route path="contacts" element={<PlaceholderPage />} />
            <Route path="media" element={<PlaceholderPage />} />
            <Route path="timings" element={<PlaceholderPage />} />
            <Route path="settings" element={<PlaceholderPage />} />
          </Route>
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}

export default App;
