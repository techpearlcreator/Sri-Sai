import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

export default function EventForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', description: '', content: '', featured_image: null,
    event_date: '', event_time: '', end_date: '',
    location: '', status: 'upcoming', is_featured: false,
  });
  const [categories, setCategories] = useState([]);
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    client.get('/categories', { params: { type: 'event' } })
      .then((res) => setCategories(res.data.data))
      .catch(() => {});

    if (item) {
      client.get(`/events/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', description: d.description || '',
          content: d.content || '', featured_image: d.featured_image || null,
          event_date: d.event_date ? d.event_date.slice(0, 10) : '',
          event_time: d.event_time || '', end_date: d.end_date ? d.end_date.slice(0, 10) : '',
          location: d.location || '', status: d.status || 'upcoming',
          is_featured: d.is_featured || false,
          category_id: d.category_id || '',
        });
      }).catch(() => toast.error('Failed to load event'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_featured: form.is_featured ? 1 : 0, category_id: form.category_id || null };
      if (isEdit) {
        await client.put(`/events/${item.id}`, payload);
        toast.success('Event updated');
      } else {
        await client.post('/events', payload);
        toast.success('Event created');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Event' : 'New Event'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Title</label>
          <input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={3} />
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Event Date</label>
            <input type="date" value={form.event_date} onChange={(e) => handleChange('event_date', e.target.value)} required />
          </div>
          <div className="form-group">
            <label>Time</label>
            <input type="time" value={form.event_time} onChange={(e) => handleChange('event_time', e.target.value)} />
          </div>
          <div className="form-group">
            <label>End Date</label>
            <input type="date" value={form.end_date} onChange={(e) => handleChange('end_date', e.target.value)} />
          </div>
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Location</label>
            <input value={form.location} onChange={(e) => handleChange('location', e.target.value)} />
          </div>
          <div className="form-group">
            <label>Status</label>
            <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
              <option value="upcoming">Upcoming</option>
              <option value="ongoing">Ongoing</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>

        <div className="form-group">
          <label>Description</label>
          <textarea rows={3} value={form.description} onChange={(e) => handleChange('description', e.target.value)} />
        </div>

        <div className="form-group">
          <label>Content</label>
          <textarea rows={6} value={form.content} onChange={(e) => handleChange('content', e.target.value)} />
        </div>

        <div className="form-group">
          <label>Featured Image</label>
          <ImageUploader value={form.featured_image} onChange={(v) => handleChange('featured_image', v)} module="events" />
        </div>

        <div className="form-group">
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <input type="checkbox" checked={form.is_featured} onChange={(e) => handleChange('is_featured', e.target.checked)} />
            Featured event
          </label>
        </div>

        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>
            {saving ? 'Saving...' : isEdit ? 'Update' : 'Create'}
          </button>
        </div>
      </form>
    </Modal>
  );
}
