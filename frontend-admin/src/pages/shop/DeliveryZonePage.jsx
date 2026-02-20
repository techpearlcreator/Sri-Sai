import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';

const emptyForm = { name: '', min_km: '', max_km: '', charge: '', is_active: true };

function ZoneForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState(emptyForm);
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      setForm({
        name:      item.name || '',
        min_km:    item.min_km ?? '',
        max_km:    item.max_km ?? '',
        charge:    item.charge ?? '',
        is_active: !!item.is_active,
      });
    } else {
      setForm(emptyForm);
    }
  }, [item]);

  const handleChange = (key, value) => setForm((p) => ({ ...p, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_active: form.is_active ? 1 : 0 };
      if (isEdit) {
        await client.put(`/delivery-zones/${item.id}`, payload);
        toast.success('Zone updated');
      } else {
        await client.post('/delivery-zones', payload);
        toast.success('Zone created');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally {
      setSaving(false);
    }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Delivery Zone' : 'New Delivery Zone'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Zone Name <span style={{ color: 'red' }}>*</span></label>
          <input value={form.name} onChange={(e) => handleChange('name', e.target.value)}
            placeholder="e.g. Local (0–5 km)" required minLength={3} />
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Min Distance (km)</label>
            <input type="number" step="0.1" min="0" value={form.min_km}
              onChange={(e) => handleChange('min_km', e.target.value)} placeholder="0" />
            <small style={{ color: 'var(--text-light)' }}>Inclusive lower bound (≥)</small>
          </div>
          <div className="form-group">
            <label>Max Distance (km)</label>
            <input type="number" step="0.1" min="0" value={form.max_km}
              onChange={(e) => handleChange('max_km', e.target.value)} placeholder="10" />
            <small style={{ color: 'var(--text-light)' }}>
              Exclusive upper bound (&lt;). Set <strong>0</strong> for unlimited (last zone).
            </small>
          </div>
          <div className="form-group">
            <label>Delivery Charge (₹) <span style={{ color: 'red' }}>*</span></label>
            <input type="number" step="0.01" min="0" value={form.charge}
              onChange={(e) => handleChange('charge', e.target.value)} placeholder="100" required />
            <small style={{ color: 'var(--text-light)' }}>Use 0 for free delivery.</small>
          </div>
        </div>

        <div className="form-group">
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <input type="checkbox" checked={form.is_active}
              onChange={(e) => handleChange('is_active', e.target.checked)} />
            Active (charges apply to orders in this distance range)
          </label>
        </div>

        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>
            {saving ? 'Saving...' : isEdit ? 'Update Zone' : 'Create Zone'}
          </button>
        </div>
      </form>
    </Modal>
  );
}

export default function DeliveryZonePage() {
  const [zones, setZones] = useState([]);
  const [loading, setLoading] = useState(true);
  const [formItem, setFormItem] = useState(null);
  const [showForm, setShowForm] = useState(false);
  const [deleteId, setDeleteId] = useState(null);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const res = await client.get('/delivery-zones');
      setZones(res.data.data || []);
    } catch {
      toast.error('Failed to load delivery zones');
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => { load(); }, [load]);

  const openNew  = () => { setFormItem(null); setShowForm(true); };
  const openEdit = (zone) => { setFormItem(zone); setShowForm(true); };
  const onSaved  = () => { setShowForm(false); load(); };

  const confirmDelete = async () => {
    if (!deleteId) return;
    try {
      await client.delete(`/delivery-zones/${deleteId}`);
      toast.success('Zone deleted');
      setDeleteId(null);
      load();
    } catch {
      toast.error('Delete failed');
    }
  };

  const fmtRange = (z) => {
    const min = parseFloat(z.min_km) || 0;
    const max = parseFloat(z.max_km);
    return `${min} km – ${max > 0 ? max + ' km' : '∞'}`;
  };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <div>
          <h2 style={{ margin: 0 }}>Delivery Zones</h2>
          <p style={{ margin: '4px 0 0', color: 'var(--text-light)', fontSize: '0.88rem' }}>
            Distance-based delivery charge slabs — calculated straight-line (Haversine) from the shop dispatch point.
          </p>
        </div>
        <button className="btn btn-primary" onClick={openNew}>+ New Zone</button>
      </div>

      {loading ? (
        <p style={{ color: 'var(--text-light)' }}>Loading...</p>
      ) : zones.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '3rem' }}>
          <p style={{ color: 'var(--text-muted)' }}>No delivery zones configured yet.</p>
          <p style={{ fontSize: '0.85rem', color: 'var(--text-light)' }}>
            Create zones like "0–5 km = Free", "5–15 km = ₹50", "15–30 km = ₹100", etc.
          </p>
          <button className="btn btn-primary" style={{ marginTop: '1rem' }} onClick={openNew}>Create First Zone</button>
        </div>
      ) : (
        <div className="card" style={{ overflow: 'auto' }}>
          <table className="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Zone Name</th>
                <th>Distance Range</th>
                <th>Charge (₹)</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {zones.map((z, i) => (
                <tr key={z.id}>
                  <td style={{ color: 'var(--text-light)', fontSize: '0.8rem' }}>{i + 1}</td>
                  <td><strong>{z.name}</strong></td>
                  <td style={{ whiteSpace: 'nowrap', fontFamily: 'monospace' }}>
                    {fmtRange(z)}
                  </td>
                  <td>
                    <strong style={{ color: 'var(--primary)' }}>
                      {parseFloat(z.charge) === 0 ? '🆓 Free' : `₹${parseFloat(z.charge).toFixed(2)}`}
                    </strong>
                  </td>
                  <td>
                    <span className={`status-badge status-${z.is_active ? 'published' : 'draft'}`}>
                      {z.is_active ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td>
                    <button className="btn btn-sm btn-outline" onClick={() => openEdit(z)}>Edit</button>
                    <button className="btn btn-sm btn-danger" style={{ marginLeft: '0.5rem' }} onClick={() => setDeleteId(z.id)}>Delete</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {showForm && <ZoneForm item={formItem} onClose={() => setShowForm(false)} onSaved={onSaved} />}

      {deleteId && (
        <Modal isOpen onClose={() => setDeleteId(null)} title="Confirm Delete">
          <p>Delete this delivery zone? Future orders within its distance range will have no delivery charge applied.</p>
          <div className="form-actions">
            <button className="btn btn-outline" onClick={() => setDeleteId(null)}>Cancel</button>
            <button className="btn btn-danger" onClick={confirmDelete}>Delete Zone</button>
          </div>
        </Modal>
      )}
    </div>
  );
}
