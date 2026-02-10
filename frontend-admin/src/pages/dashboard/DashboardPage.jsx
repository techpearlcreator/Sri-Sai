import { useState, useEffect } from 'react';
import client from '../../api/client';
import {
  HiOutlineDocumentText, HiOutlineBookOpen, HiOutlineCalendar,
  HiOutlinePhotograph, HiOutlineUserGroup, HiOutlineCurrencyRupee,
  HiOutlineMail, HiOutlineCollection
} from 'react-icons/hi';

const statCards = [
  { key: 'blogs',      label: 'Blog Posts',  icon: HiOutlineDocumentText, color: '#5F2C70' },
  { key: 'magazines',  label: 'Magazines',   icon: HiOutlineBookOpen,     color: '#1D0427' },
  { key: 'events',     label: 'Events',      icon: HiOutlineCalendar,     color: '#9FA73E' },
  { key: 'trustees',   label: 'Trustees',    icon: HiOutlineUserGroup,    color: '#724D67' },
  { key: 'contacts',   label: 'Messages',    icon: HiOutlineMail,         color: '#c0392b' },
  { key: 'pages',      label: 'Pages',       icon: HiOutlineCollection,   color: '#2980b9' },
];

export default function DashboardPage() {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    client.get('/dashboard')
      .then((res) => setData(res.data.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="loading">Loading dashboard...</div>;
  if (!data) return <div className="error">Failed to load dashboard</div>;

  return (
    <div className="dashboard">
      <h1 className="page-title">Dashboard</h1>

      <div className="stat-grid">
        {statCards.map(({ key, label, icon: Icon, color }) => {
          const val = data.counts[key];
          const total = typeof val === 'object' ? val.total : val;
          const sub = typeof val === 'object'
            ? Object.entries(val).filter(([k]) => k !== 'total').map(([k, v]) => `${v} ${k}`).join(', ')
            : null;

          return (
            <div key={key} className="stat-card" style={{ borderLeftColor: color }}>
              <div className="stat-card__icon" style={{ color }}>
                <Icon size={32} />
              </div>
              <div className="stat-card__info">
                <span className="stat-card__total">{total}</span>
                <span className="stat-card__label">{label}</span>
                {sub && <span className="stat-card__sub">{sub}</span>}
              </div>
            </div>
          );
        })}

        {/* Donations card with amount */}
        <div className="stat-card" style={{ borderLeftColor: '#27ae60' }}>
          <div className="stat-card__icon" style={{ color: '#27ae60' }}>
            <HiOutlineCurrencyRupee size={32} />
          </div>
          <div className="stat-card__info">
            <span className="stat-card__total">{data.counts.donations?.total || 0}</span>
            <span className="stat-card__label">Donations</span>
            <span className="stat-card__sub">₹{(data.counts.donations?.amount || 0).toLocaleString()}</span>
          </div>
        </div>

        {/* Gallery */}
        <div className="stat-card" style={{ borderLeftColor: '#e67e22' }}>
          <div className="stat-card__icon" style={{ color: '#e67e22' }}>
            <HiOutlinePhotograph size={32} />
          </div>
          <div className="stat-card__info">
            <span className="stat-card__total">{data.counts.gallery_albums || 0}</span>
            <span className="stat-card__label">Albums</span>
            <span className="stat-card__sub">{data.counts.gallery_images || 0} images</span>
          </div>
        </div>
      </div>

      {/* Recent Activity */}
      <div className="activity-section">
        <h2>Recent Activity</h2>
        <div className="activity-list">
          {data.recent_activity.map((a) => (
            <div key={a.id} className="activity-item">
              <span className={`activity-badge activity-badge--${a.action}`}>{a.action}</span>
              <span className="activity-desc">{a.description}</span>
              <span className="activity-user">{a.user_name || 'System'}</span>
              <span className="activity-time">{new Date(a.created_at).toLocaleString()}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
