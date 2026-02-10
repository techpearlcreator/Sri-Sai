import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

export default function GalleryForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', description: '', cover_image: null, status: 'published',
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/gallery/albums/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', description: d.description || '',
          cover_image: d.cover_image || null, status: d.status || 'published',
        });
      }).catch(() => toast.error('Failed to load album'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (isEdit) {
        await client.put(`/gallery/albums/${item.id}`, form);
        toast.success('Album updated');
      } else {
        await client.post('/gallery/albums', form);
        toast.success('Album created');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Album' : 'New Album'}>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Title</label>
          <input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={2} />
        </div>
        <div className="form-group">
          <label>Status</label>
          <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
          </select>
        </div>
        <div className="form-group">
          <label>Description</label>
          <textarea rows={3} value={form.description} onChange={(e) => handleChange('description', e.target.value)} />
        </div>
        <div className="form-group">
          <label>Cover Image</label>
          <ImageUploader value={form.cover_image} onChange={(v) => handleChange('cover_image', v)} module="gallery" />
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
