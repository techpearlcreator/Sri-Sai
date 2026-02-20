import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

export default function BlogForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', title_ta: '', content: '', content_ta: '',
    excerpt: '', excerpt_ta: '', featured_image: null,
    category_id: '', status: 'draft', is_featured: false,
  });
  const [categories, setCategories] = useState([]);
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    client.get('/categories', { params: { type: 'blog' } })
      .then((res) => setCategories(res.data.data))
      .catch(() => {});

    if (item) {
      client.get(`/blogs/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', title_ta: d.title_ta || '',
          content: d.content || '', content_ta: d.content_ta || '',
          excerpt: d.excerpt || '', excerpt_ta: d.excerpt_ta || '',
          featured_image: d.featured_image || null,
          category_id: d.category_id || '', status: d.status || 'draft',
          is_featured: d.is_featured || false,
        });
      }).catch(() => toast.error('Failed to load blog'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, category_id: form.category_id || null, is_featured: form.is_featured ? 1 : 0 };
      if (isEdit) {
        await client.put(`/blogs/${item.id}`, payload);
        toast.success('Blog updated');
      } else {
        await client.post('/blogs', payload);
        toast.success('Blog created');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Blog Post' : 'New Blog Post'} wide>
      <form onSubmit={handleSubmit}>

        <div className="form-row">
          <div className="form-group">
            <label>Category</label>
            <select value={form.category_id} onChange={(e) => handleChange('category_id', e.target.value)}>
              <option value="">— None —</option>
              {categories.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
            </select>
          </div>
          <div className="form-group">
            <label>Status</label>
            <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
          </div>
        </div>

        <div className="bilingual-grid">
          <div className="bilingual-col">
            <div className="bilingual-col-header col-en">🇬🇧 English</div>
            <div className="form-group">
              <label>Title *</label>
              <input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={5} />
            </div>
            <div className="form-group">
              <label>Excerpt</label>
              <textarea rows={3} value={form.excerpt} onChange={(e) => handleChange('excerpt', e.target.value)} />
            </div>
            <div className="form-group">
              <label>Content *</label>
              <textarea rows={9} value={form.content} onChange={(e) => handleChange('content', e.target.value)} required minLength={10} />
            </div>
          </div>

          <div className="bilingual-col">
            <div className="bilingual-col-header col-ta">🇮🇳 தமிழ்</div>
            <div className="form-group">
              <label>தலைப்பு (Title)</label>
              <input value={form.title_ta} onChange={(e) => handleChange('title_ta', e.target.value)} placeholder="தமிழில் தலைப்பு" />
            </div>
            <div className="form-group">
              <label>சுருக்கம் (Excerpt)</label>
              <textarea rows={3} value={form.excerpt_ta} onChange={(e) => handleChange('excerpt_ta', e.target.value)} placeholder="தமிழில் சுருக்கம்" />
            </div>
            <div className="form-group">
              <label>உள்ளடக்கம் (Content)</label>
              <textarea rows={9} value={form.content_ta} onChange={(e) => handleChange('content_ta', e.target.value)} placeholder="தமிழில் உள்ளடக்கம்" />
            </div>
          </div>
        </div>

        <div className="form-group">
          <label>Featured Image</label>
          <ImageUploader value={form.featured_image} onChange={(v) => handleChange('featured_image', v)} module="blogs" />
        </div>

        <div className="form-group">
          <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
            <input type="checkbox" checked={form.is_featured} onChange={(e) => handleChange('is_featured', e.target.checked)} />
            Featured post
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
