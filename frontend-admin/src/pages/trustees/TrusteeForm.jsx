import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

export default function TrusteeForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    name: '', designation: '', bio: '', photo: null,
    phone: '', email: '', trustee_type: 'co_opted',
    display_order: 0, is_active: true,
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/trustees/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          name: d.name || '', designation: d.designation || '',
          bio: d.bio || '', photo: d.photo || null,
          phone: d.phone || '', email: d.email || '',
          trustee_type: d.trustee_type || 'co_opted',
          display_order: d.display_order || 0,
          is_active: d.is_active !== undefined ? !!d.is_active : true,
        });
      }).catch(() => toast.error('Failed to load trustee'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_active: form.is_active ? 1 : 0 };
      if (isEdit) {
        await client.put(`/trustees/${item.id}`, payload);
        toast.success('Trustee updated');
      } else {
        await client.post('/trustees', payload);
        toast.success('Trustee added');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Trustee' : 'Add Trustee'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group">
            <label>Name</label>
            <input value={form.name} onChange={(e) => handleChange('name', e.target.value)} required minLength={2} />
          </div>
          <div className="form-group">
            <label>Designation</label>
            <input value={form.designation} onChange={(e) => handleChange('designation', e.target.value)} />
          </div>
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Type</label>
            <select value={form.trustee_type} onChange={(e) => handleChange('trustee_type', e.target.value)}>
              <option value="main">Main Trustee</option>
              <option value="co_opted">Co-opted Trustee</option>
            </select>
          </div>
          <div className="form-group">
            <label>Display Order</label>
            <input type="number" value={form.display_order} onChange={(e) => handleChange('display_order', parseInt(e.target.value) || 0)} min={0} />
          </div>
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Phone</label>
            <input value={form.phone} onChange={(e) => handleChange('phone', e.target.value)} />
          </div>
          <div className="form-group">
            <label>Email</label>
            <input type="email" value={form.email} onChange={(e) => handleChange('email', e.target.value)} />
          </div>
        </div>

        <div className="form-group">
          <label>Bio</label>
          <textarea rows={4} value={form.bio} onChange={(e) => handleChange('bio', e.target.value)} />
        </div>

        <div className="form-group">
          <label>Photo</label>
          <ImageUploader value={form.photo} onChange={(v) => handleChange('photo', v)} module="trustees" />
        </div>

        <div className="form-group">
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <input type="checkbox" checked={form.is_active} onChange={(e) => handleChange('is_active', e.target.checked)} />
            Active
          </label>
        </div>

        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>
            {saving ? 'Saving...' : isEdit ? 'Update' : 'Add'}
          </button>
        </div>
      </form>
    </Modal>
  );
}
