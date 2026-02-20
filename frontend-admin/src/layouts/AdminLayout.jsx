import { useState } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { useLang } from '../contexts/LangContext';
import toast from 'react-hot-toast';
import {
  HiOutlineHome, HiOutlineDocumentText, HiOutlineBookOpen,
  HiOutlinePhotograph, HiOutlineCalendar,
  HiOutlineUserGroup, HiOutlineCurrencyRupee, HiOutlineMail,
  HiOutlineCog, HiOutlineClock, HiOutlinePhotograph as HiMedia,
  HiOutlineLogout, HiOutlineMenu, HiOutlineX,
  HiOutlineShoppingBag, HiOutlineClipboardList, HiOutlineGlobe, HiOutlineTicket
} from 'react-icons/hi';

const navKeys = [
  { path: '/',           key: 'sidebar.dashboard',    icon: HiOutlineHome },
  { path: '/blogs',      key: 'sidebar.blogs',        icon: HiOutlineDocumentText },
  { path: '/magazines',  key: 'sidebar.magazines',    icon: HiOutlineBookOpen },
  { path: '/gallery',    key: 'sidebar.gallery',      icon: HiOutlinePhotograph },
  { path: '/events',     key: 'sidebar.events',       icon: HiOutlineCalendar },
  { path: '/trustees',   key: 'sidebar.trustees',     icon: HiOutlineUserGroup },
  { path: '/donations',  key: 'sidebar.donations',    icon: HiOutlineCurrencyRupee },
  { path: '/contacts',   key: 'sidebar.messages',     icon: HiOutlineMail },
  { path: '/media',      key: 'sidebar.media',        icon: HiMedia },
  { path: '/timings',    key: 'sidebar.timings',      icon: HiOutlineClock },
  { key: 'sidebar.section_shop', divider: true },
  { path: '/products',       key: 'sidebar.products',       icon: HiOutlineShoppingBag },
  { path: '/pooja-types',    key: 'sidebar.pooja_types',    icon: HiOutlineClipboardList },
  { path: '/pooja-bookings', key: 'sidebar.pooja_bookings', icon: HiOutlineTicket },
  { path: '/shop-enquiries',   key: 'sidebar.enquiries',        icon: HiOutlineMail },
  { path: '/shop-orders',      key: 'sidebar.shop_orders',      icon: HiOutlineClipboardList },
  { path: '/delivery-zones',   key: 'sidebar.delivery_zones',   icon: HiOutlineGlobe },
  { path: '/shop-location',    key: 'sidebar.shop_location',    icon: HiOutlineCog },
  { key: 'sidebar.section_tours', divider: true },
  { path: '/tours',          key: 'sidebar.tours',          icon: HiOutlineGlobe },
  { path: '/tour-bookings',  key: 'sidebar.tour_bookings',  icon: HiOutlineTicket },
  { key: '', divider: true },
  { path: '/settings',   key: 'sidebar.settings',     icon: HiOutlineCog },
];

export default function AdminLayout() {
  const { user, logout } = useAuth();
  const { locale, toggle, t } = useLang();
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const handleLogout = async () => {
    await logout();
    toast.success('Logged out');
    navigate('/login', { replace: true });
  };

  return (
    <div className="admin-layout">
      {/* Sidebar */}
      <aside className={`sidebar ${sidebarOpen ? 'sidebar--open' : ''}`}>
        <div className="sidebar__header">
          <div className="sidebar__brand">
            <span className="sidebar__logo">ॐ</span>
            <div>
              <h2>Sri Sai Mission</h2>
              <span className="sidebar__subtitle">Admin Panel</span>
            </div>
          </div>
          <button className="sidebar__close" onClick={() => setSidebarOpen(false)}>
            <HiOutlineX size={20} />
          </button>
        </div>
        <nav className="sidebar__nav">
          {navKeys.map((item, idx) => {
            if (item.divider) {
              return item.key ? (
                <div key={`div-${idx}`} className="sidebar__divider">
                  <span>{t(item.key)}</span>
                </div>
              ) : <hr key={`div-${idx}`} className="sidebar__hr" />;
            }
            const Icon = item.icon;
            return (
              <NavLink
                key={item.path}
                to={item.path}
                end={item.path === '/'}
                className={({ isActive }) => `sidebar__link ${isActive ? 'sidebar__link--active' : ''}`}
                onClick={() => setSidebarOpen(false)}
              >
                <Icon size={20} />
                <span>{t(item.key)}</span>
              </NavLink>
            );
          })}
        </nav>
      </aside>

      {/* Overlay for mobile */}
      {sidebarOpen && <div className="sidebar__overlay" onClick={() => setSidebarOpen(false)} />}

      {/* Main content */}
      <div className="main-content">
        {/* Top bar */}
        <header className="topbar">
          <button className="topbar__menu" onClick={() => setSidebarOpen(true)}>
            <HiOutlineMenu size={24} />
          </button>
          <div className="topbar__spacer" />
          <button
            className="topbar__lang"
            onClick={toggle}
            title="Switch language"
          >
            {locale === 'en' ? 'EN | தமிழ்' : 'தமிழ் | EN'}
          </button>
          <div className="topbar__user">
            <span className="topbar__name">{user?.name}</span>
            <span className="topbar__role">{user?.role_name}</span>
          </div>
          <button className="topbar__logout" onClick={handleLogout} title="Logout">
            <HiOutlineLogout size={20} />
          </button>
        </header>

        {/* Page content */}
        <main className="page-content">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
