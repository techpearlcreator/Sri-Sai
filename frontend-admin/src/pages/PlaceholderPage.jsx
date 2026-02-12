import { useLocation } from 'react-router-dom';

export default function PlaceholderPage() {
  const { pathname } = useLocation();
  const name = pathname.replace('/', '').replace(/-/g, ' ');

  return (
    <div className="placeholder-page">
      <h1 className="page-title" style={{ textTransform: 'capitalize' }}>{name || 'Page'}</h1>
      <p style={{ color: '#666', marginTop: '1rem' }}>
        This module will be built in Phase 7. The API endpoints are ready.
      </p>
    </div>
  );
}
