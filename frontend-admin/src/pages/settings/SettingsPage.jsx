import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';

const GROUPS = [
  { key: 'general', label: 'General' },
  { key: 'contact', label: 'Contact' },
  { key: 'social', label: 'Social Media' },
  { key: 'seo', label: 'SEO' },
];

export default function SettingsPage() {
  const [settings, setSettings] = useState({});
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [activeGroup, setActiveGroup] = useState('general');

  useEffect(() => {
    setLoading(true);
    client.get('/settings')
      .then((res) => {
        const map = {};
        (res.data.data || []).forEach((s) => { map[s.setting_key] = s; });
        setSettings(map);
      })
      .catch(() => toast.error('Failed to load settings'))
      .finally(() => setLoading(false));
  }, []);

  const handleChange = (key, value) => {
    setSettings((prev) => ({
      ...prev,
      [key]: { ...prev[key], setting_value: value },
    }));
  };

  const handleSave = async () => {
    setSaving(true);
    try {
      const groupSettings = Object.values(settings)
        .filter((s) => s.setting_group === activeGroup)
        .map((s) => ({ setting_key: s.setting_key, setting_value: s.setting_value }));
      await client.put('/settings', { settings: groupSettings });
      toast.success('Settings saved');
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  const groupSettings = Object.values(settings).filter((s) => s.setting_group === activeGroup);

  if (loading) return <div className="loading"><div className="spinner" style={{ margin: '0 auto' }} /></div>;

  return (
    <div>
      <h1 className="page-title">Settings</h1>

      <div className="settings-tabs">
        {GROUPS.map((g) => (
          <button
            key={g.key}
            className={`settings-tab ${activeGroup === g.key ? 'settings-tab--active' : ''}`}
            onClick={() => setActiveGroup(g.key)}
          >
            {g.label}
          </button>
        ))}
      </div>

      <div className="settings-panel">
        {groupSettings.length === 0 ? (
          <p style={{ color: '#666' }}>No settings in this group.</p>
        ) : (
          groupSettings.map((s) => (
            <div key={s.setting_key} className="form-group">
              <label>{(s.setting_key || '').replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())}</label>
              {(s.setting_value || '').length > 100 ? (
                <textarea
                  rows={3}
                  value={s.setting_value || ''}
                  onChange={(e) => handleChange(s.setting_key, e.target.value)}
                />
              ) : (
                <input
                  value={s.setting_value || ''}
                  onChange={(e) => handleChange(s.setting_key, e.target.value)}
                />
              )}
            </div>
          ))
        )}
        {groupSettings.length > 0 && (
          <div className="form-actions">
            <button className="btn btn-primary" onClick={handleSave} disabled={saving}>
              {saving ? 'Saving...' : 'Save Settings'}
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
