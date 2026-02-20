import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';

export default function TimingForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', title_ta: '', description: '', day_type: 'daily',
    start_time: '', end_time: '', location: '', sort_order: 0, is_active: true,
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/temple-timings/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', title_ta: d.title_ta || '',
          description: d.description || '', day_type: d.day_type || 'daily',
          start_time: d.start_time || '', end_time: d.end_time || '',
          location: d.location || '', sort_order: d.sort_order || 0,
          is_active: d.is_active !== undefined ? !!d.is_active : true,
        });
      }).catch(() => toast.error('Failed to load timing'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_active: form.is_active ? 1 : 0 };
      if (isEdit) { await client.put(`/temple-timings/${item.id}`, payload); toast.success('Timing updated'); }
      else { await client.post('/temple-timings', payload); toast.success('Timing added'); }
      onSaved();
    } catch (err) { toast.error(err.response?.data?.error?.message || 'Save failed'); }
    finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Timing' : 'Add Timing'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group"><label>Day Type</label>
            <select value={form.day_type} onChange={(e) => handleChange('day_type', e.target.value)}>
              <option value="daily">Daily</option><option value="weekday">Weekday</option>
              <option value="weekend">Weekend</option><option value="special">Special</option>
              <option value="monday">Monday</option><option value="tuesday">Tuesday</option>
              <option value="wednesday">Wednesday</option><option value="thursday">Thursday</option>
              <option value="friday">Friday</option><option value="saturday">Saturday</option>
              <option value="sunday">Sunday</option>
            </select>
          </div>
          <div className="form-group"><label>Location / Temple</label><input value={form.location} onChange={(e) => handleChange('location', e.target.value)} /></div>
        </div>
        <div className="form-row">
          <div className="form-group"><label>Start Time *</label><input type="time" value={form.start_time} onChange={(e) => handleChange('start_time', e.target.value)} required /></div>
          <div className="form-group"><label>End Time</label><input type="time" value={form.end_time} onChange={(e) => handleChange('end_time', e.target.value)} /></div>
          <div className="form-group"><label>Display Order</label><input type="number" value={form.sort_order} onChange={(e) => handleChange('sort_order', parseInt(e.target.value) || 0)} min={0} /></div>
        </div>
        <div className="bilingual-grid">
          <div className="bilingual-col">
            <div className="bilingual-col-header col-en">🇬🇧 English</div>
            <div className="form-group"><label>Pooja / Event Name *</label><input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={2} /></div>
            <div className="form-group"><label>Description</label><textarea rows={3} value={form.description} onChange={(e) => handleChange('description', e.target.value)} /></div>
          </div>
          <div className="bilingual-col">
            <div className="bilingual-col-header col-ta">🇮🇳 தமிழ்</div>
            <div className="form-group"><label>பூஜை / நிகழ்வு பெயர் (Name)</label><input value={form.title_ta} onChange={(e) => handleChange('title_ta', e.target.value)} placeholder="தமிழில் பூஜை பெயர்" /></div>
            <div className="form-group"><label style={{ color: 'var(--text-muted)', fontStyle: 'italic', fontSize: '0.8rem' }}>விளக்கம் (Description) — optional</label><textarea rows={3} disabled style={{ opacity: 0.4 }} /></div>
          </div>
        </div>
        <div className="form-group"><label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><input type="checkbox" checked={form.is_active} onChange={(e) => handleChange('is_active', e.target.checked)} />Active</label></div>
        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>{saving ? 'Saving...' : isEdit ? 'Update' : 'Add'}</button>
        </div>
      </form>
    </Modal>
  );
}
