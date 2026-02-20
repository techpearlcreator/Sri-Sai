import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { Toaster } from 'react-hot-toast';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import { LangProvider } from './contexts/LangContext';
import ProtectedRoute from './components/ProtectedRoute';
import AdminLayout from './layouts/AdminLayout';
import LoginPage from './pages/auth/LoginPage';
import DashboardPage from './pages/dashboard/DashboardPage';
import BlogList from './pages/blogs/BlogList';
import MagazineList from './pages/magazines/MagazineList';
import GalleryList from './pages/gallery/GalleryList';
import EventList from './pages/events/EventList';
import TrusteeList from './pages/trustees/TrusteeList';
import DonationList from './pages/donations/DonationList';
import ContactList from './pages/contacts/ContactList';
import MediaLibrary from './pages/media/MediaLibrary';
import SettingsPage from './pages/settings/SettingsPage';
import TimingList from './pages/timings/TimingList';
import ProductList from './pages/shop/ProductList';
import PoojaTypeList from './pages/shop/PoojaTypeList';
import PoojaBookingList from './pages/shop/PoojaBookingList';
import ShopEnquiryList from './pages/shop/ShopEnquiryList';
import ShopOrdersPage from './pages/shop/ShopOrdersPage';
import DeliveryZonePage from './pages/shop/DeliveryZonePage';
import ShopSettingsPage from './pages/shop/ShopSettingsPage';
import TourList from './pages/tours/TourList';
import TourBookingList from './pages/tours/TourBookingList';
import './App.css';

function LoginRoute() {
  const { user, loading } = useAuth();
  if (loading) return null;
  if (user) return <Navigate to="/" replace />;
  return <LoginPage />;
}

function App() {
  return (
    <BrowserRouter basename="/srisai/public/admin">
      <LangProvider>
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
              <Route path="blogs" element={<BlogList />} />
              <Route path="magazines" element={<MagazineList />} />
              <Route path="gallery" element={<GalleryList />} />
              <Route path="events" element={<EventList />} />
              <Route path="trustees" element={<TrusteeList />} />
              <Route path="donations" element={<DonationList />} />
              <Route path="contacts" element={<ContactList />} />
              <Route path="media" element={<MediaLibrary />} />
              <Route path="timings" element={<TimingList />} />
              <Route path="settings" element={<SettingsPage />} />
              <Route path="products" element={<ProductList />} />
              <Route path="pooja-types" element={<PoojaTypeList />} />
              <Route path="pooja-bookings" element={<PoojaBookingList />} />
              <Route path="shop-enquiries" element={<ShopEnquiryList />} />
              <Route path="shop-orders" element={<ShopOrdersPage />} />
              <Route path="delivery-zones" element={<DeliveryZonePage />} />
              <Route path="shop-location" element={<ShopSettingsPage />} />
              <Route path="tours" element={<TourList />} />
              <Route path="tour-bookings" element={<TourBookingList />} />
            </Route>
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
        </AuthProvider>
      </LangProvider>
    </BrowserRouter>
  );
}

export default App;
