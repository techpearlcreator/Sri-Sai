import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';

export default function PoojaTypeForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    name: '', name_ta: '', temple: 'perungalathur', description: '', description_ta: '',
    duration: '', price: '', is_active: true, sort_order: 0,
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/pooja-types/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          name: d.name || '', name_ta: d.name_ta || '',
          temple: d.temple || 'perungalathur',
          description: d.description || '', description_ta: d.description_ta || '',
          duration: d.duration || '', price: d.price || '',
          is_active: !!d.is_active, sort_order: d.sort_order || 0,
        });
      }).catch(() => toast.error('Failed to load pooja type'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_active: form.is_active ? 1 : 0 };
      if (isEdit) { await client.put(`/pooja-types/${item.id}`, payload); toast.success('Pooja type updated'); }
      else { await client.post('/pooja-types', payload); toast.success('Pooja type created'); }
      onSaved();
    } catch (err) { toast.error(err.response?.data?.error?.message || 'Save failed'); }
    finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Pooja Type' : 'New Pooja Type'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group"><label>Temple</label>
            <select value={form.temple} onChange={(e) => handleChange('temple', e.target.value)}>
              <option value="perungalathur">Perungalathur</option>
              <option value="keerapakkam">Keerapakkam</option>
              <option value="both">Both Temples</option>
            </select>
          </div>
          <div className="form-group"><label>Price (₹) *</label><input type="number" step="0.01" min="0" value={form.price} onChange={(e) => handleChange('price', e.target.value)} required /></div>
          <div className="form-group"><label>Duration (e.g. "30 mins")</label><input value={form.duration} onChange={(e) => handleChange('duration', e.target.value)} /></div>
          <div className="form-group"><label>Sort Order</label><input type="number" value={form.sort_order} onChange={(e) => handleChange('sort_order', parseInt(e.target.value) || 0)} /></div>
        </div>
        <div className="bilingual-grid">
          <div className="bilingual-col">
            <div className="bilingual-col-header col-en">🇬🇧 English</div>
            <div className="form-group"><label>Pooja Name *</label><input value={form.name} onChange={(e) => handleChange('name', e.target.value)} required minLength={2} /></div>
            <div className="form-group"><label>Description</label><textarea rows={4} value={form.description} onChange={(e) => handleChange('description', e.target.value)} /></div>
          </div>
          <div className="bilingual-col">
            <div className="bilingual-col-header col-ta">🇮🇳 தமிழ்</div>
            <div className="form-group"><label>பூஜை பெயர் (Pooja Name)</label><input value={form.name_ta} onChange={(e) => handleChange('name_ta', e.target.value)} placeholder="தமிழில் பூஜை பெயர்" /></div>
            <div className="form-group"><label>விளக்கம் (Description)</label><textarea rows={4} value={form.description_ta} onChange={(e) => handleChange('description_ta', e.target.value)} placeholder="தமிழில் விளக்கம்" /></div>
          </div>
        </div>
        <div className="form-group"><label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><input type="checkbox" checked={form.is_active} onChange={(e) => handleChange('is_active', e.target.checked)} />Active</label></div>
        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>{saving ? 'Saving...' : isEdit ? 'Update' : 'Create'}</button>
        </div>
      </form>
    </Modal>
  );
}
