import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

export default function TourForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', title_ta: '', destination: '', destination_ta: '',
    description: '', description_ta: '', featured_image: null,
    start_date: '', end_date: '', price_per_person: '',
    max_seats: 50, status: 'upcoming', is_active: true,
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/tours/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', title_ta: d.title_ta || '',
          destination: d.destination || '', destination_ta: d.destination_ta || '',
          description: d.description || '', description_ta: d.description_ta || '',
          featured_image: d.featured_image || null,
          start_date: d.start_date ? d.start_date.slice(0, 10) : '',
          end_date: d.end_date ? d.end_date.slice(0, 10) : '',
          price_per_person: d.price_per_person || '', max_seats: d.max_seats || 50,
          status: d.status || 'upcoming', is_active: !!d.is_active,
        });
      }).catch(() => toast.error('Failed to load tour'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_active: form.is_active ? 1 : 0 };
      if (isEdit) { await client.put(`/tours/${item.id}`, payload); toast.success('Tour updated'); }
      else { await client.post('/tours', payload); toast.success('Tour created'); }
      onSaved();
    } catch (err) { toast.error(err.response?.data?.error?.message || 'Save failed'); }
    finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Tour' : 'New Tour'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group"><label>Start Date *</label><input type="date" value={form.start_date} onChange={(e) => handleChange('start_date', e.target.value)} required /></div>
          <div className="form-group"><label>End Date *</label><input type="date" value={form.end_date} onChange={(e) => handleChange('end_date', e.target.value)} required /></div>
          <div className="form-group"><label>Price Per Person (₹) *</label><input type="number" step="0.01" min="0" value={form.price_per_person} onChange={(e) => handleChange('price_per_person', e.target.value)} required /></div>
        </div>
        <div className="form-row">
          <div className="form-group"><label>Max Seats *</label><input type="number" min="1" value={form.max_seats} onChange={(e) => handleChange('max_seats', parseInt(e.target.value) || 50)} required /></div>
          <div className="form-group"><label>Status</label>
            <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
              <option value="upcoming">Upcoming</option><option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option><option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div className="bilingual-grid">
          <div className="bilingual-col">
            <div className="bilingual-col-header col-en">🇬🇧 English</div>
            <div className="form-group"><label>Tour Title *</label><input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={5} /></div>
            <div className="form-group"><label>Destination *</label><input value={form.destination} onChange={(e) => handleChange('destination', e.target.value)} required /></div>
            <div className="form-group"><label>Description</label><textarea rows={5} value={form.description} onChange={(e) => handleChange('description', e.target.value)} /></div>
          </div>
          <div className="bilingual-col">
            <div className="bilingual-col-header col-ta">🇮🇳 தமிழ்</div>
            <div className="form-group"><label>சுற்றுலா தலைப்பு (Title)</label><input value={form.title_ta} onChange={(e) => handleChange('title_ta', e.target.value)} placeholder="தமிழில் சுற்றுலா தலைப்பு" /></div>
            <div className="form-group"><label>இடம் (Destination)</label><input value={form.destination_ta} onChange={(e) => handleChange('destination_ta', e.target.value)} placeholder="தமிழில் இடம்" /></div>
            <div className="form-group"><label>விளக்கம் (Description)</label><textarea rows={5} value={form.description_ta} onChange={(e) => handleChange('description_ta', e.target.value)} placeholder="தமிழில் விளக்கம்" /></div>
          </div>
        </div>
        <div className="form-group"><label>Featured Image</label><ImageUploader value={form.featured_image} onChange={(v) => handleChange('featured_image', v)} module="tours" /></div>
        <div className="form-group"><label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><input type="checkbox" checked={form.is_active} onChange={(e) => handleChange('is_active', e.target.checked)} />Active (visible on website)</label></div>
        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>{saving ? 'Saving...' : isEdit ? 'Update' : 'Create'}</button>
        </div>
      </form>
    </Modal>
  );
}
