import { useState } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import toast from 'react-hot-toast';
import {
  HiOutlineHome, HiOutlineDocumentText, HiOutlineBookOpen,
  HiOutlinePhotograph, HiOutlineCalendar, HiOutlineCollection,
  HiOutlineUserGroup, HiOutlineCurrencyRupee, HiOutlineMail,
  HiOutlineCog, HiOutlineClock, HiOutlinePhotograph as HiMedia,
  HiOutlineLogout, HiOutlineMenu, HiOutlineX
} from 'react-icons/hi';

const navItems = [
  { path: '/',           label: 'Dashboard',    icon: HiOutlineHome },
  { path: '/blogs',      label: 'Blogs',        icon: HiOutlineDocumentText },
  { path: '/magazines',  label: 'Magazines',    icon: HiOutlineBookOpen },
  { path: '/gallery',    label: 'Gallery',       icon: HiOutlinePhotograph },
  { path: '/events',     label: 'Events',       icon: HiOutlineCalendar },
  { path: '/pages',      label: 'Pages',        icon: HiOutlineCollection },
  { path: '/trustees',   label: 'Trustees',     icon: HiOutlineUserGroup },
  { path: '/donations',  label: 'Donations',    icon: HiOutlineCurrencyRupee },
  { path: '/contacts',   label: 'Messages',     icon: HiOutlineMail },
  { path: '/media',      label: 'Media',        icon: HiMedia },
  { path: '/timings',    label: 'Timings',      icon: HiOutlineClock },
  { path: '/settings',   label: 'Settings',     icon: HiOutlineCog },
];

export default function AdminLayout() {
  const { user, logout } = useAuth();
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
          <h2>Sri Sai Admin</h2>
          <button className="sidebar__close" onClick={() => setSidebarOpen(false)}>
            <HiOutlineX size={20} />
          </button>
        </div>
        <nav className="sidebar__nav">
          {navItems.map(({ path, label, icon: Icon }) => (
            <NavLink
              key={path}
              to={path}
              end={path === '/'}
              className={({ isActive }) => `sidebar__link ${isActive ? 'sidebar__link--active' : ''}`}
              onClick={() => setSidebarOpen(false)}
            >
              <Icon size={20} />
              <span>{label}</span>
            </NavLink>
          ))}
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
