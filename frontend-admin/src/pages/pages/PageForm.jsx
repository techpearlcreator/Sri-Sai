import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';
import TranslateButton from '../../components/TranslateButton';

export default function PageForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', title_ta: '', content: '', content_ta: '', excerpt: '',
    featured_image: null, parent_id: '', menu_position: 0, status: 'published',
    show_in_menu: true, show_in_footer: false,
  });
  const [pages, setPages] = useState([]);
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    client.get('/pages', { params: { per_page: 100 } })
      .then((res) => setPages(res.data.data.filter((p) => !item || p.id !== item.id)))
      .catch(() => {});

    if (item) {
      client.get(`/pages/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', title_ta: d.title_ta || '',
          content: d.content || '', content_ta: d.content_ta || '',
          excerpt: d.excerpt || '', featured_image: d.featured_image || null,
          parent_id: d.parent_id || '', menu_position: d.menu_position || 0,
          status: d.status || 'published',
          show_in_menu: !!d.show_in_menu, show_in_footer: !!d.show_in_footer,
        });
      }).catch(() => toast.error('Failed to load page'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = {
        ...form,
        parent_id: form.parent_id || null,
        show_in_menu: form.show_in_menu ? 1 : 0,
        show_in_footer: form.show_in_footer ? 1 : 0,
      };
      if (isEdit) {
        await client.put(`/pages/${item.id}`, payload);
        toast.success('Page updated');
      } else {
        await client.post('/pages', payload);
        toast.success('Page created');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Page' : 'New Page'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Title (English)</label>
          <input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={2} />
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Parent Page</label>
            <select value={form.parent_id} onChange={(e) => handleChange('parent_id', e.target.value)}>
              <option value="">— None (top level) —</option>
              {pages.map((p) => <option key={p.id} value={p.id}>{p.title}</option>)}
            </select>
          </div>
          <div className="form-group">
            <label>Menu Position</label>
            <input type="number" value={form.menu_position} onChange={(e) => handleChange('menu_position', parseInt(e.target.value) || 0)} min={0} />
          </div>
          <div className="form-group">
            <label>Status</label>
            <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
              <option value="archived">Archived</option>
            </select>
          </div>
        </div>

        <div className="form-group">
          <label>Excerpt</label>
          <textarea rows={2} value={form.excerpt} onChange={(e) => handleChange('excerpt', e.target.value)} />
        </div>

        <div className="form-group">
          <label>Content (English)</label>
          <textarea rows={10} value={form.content} onChange={(e) => handleChange('content', e.target.value)} required />
        </div>

        <div className="form-group">
          <label>Featured Image</label>
          <ImageUploader value={form.featured_image} onChange={(v) => handleChange('featured_image', v)} module="pages" />
        </div>

        <div className="form-row">
          <div className="form-group">
            <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <input type="checkbox" checked={form.show_in_menu} onChange={(e) => handleChange('show_in_menu', e.target.checked)} />
              Show in menu
            </label>
          </div>
          <div className="form-group">
            <label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}>
              <input type="checkbox" checked={form.show_in_footer} onChange={(e) => handleChange('show_in_footer', e.target.checked)} />
              Show in footer
            </label>
          </div>
        </div>

        <hr style={{ margin: '1.5rem 0', borderColor: 'var(--border)' }} />
        <div className="tamil-section-header">
          <h4>Tamil Content / தமிழ் உள்ளடக்கம்</h4>
          <TranslateButton fields={[
            { sourceValue: form.title,   onTranslated: (v) => handleChange('title_ta',   v), label: 'Title' },
            { sourceValue: form.content, onTranslated: (v) => handleChange('content_ta', v), label: 'Content' },
          ]} />
        </div>

        <div className="form-group">
          <label>Title (Tamil / தலைப்பு)</label>
          <input value={form.title_ta} onChange={(e) => handleChange('title_ta', e.target.value)} placeholder="தமிழில் தலைப்பு" />
        </div>

        <div className="form-group">
          <label>Content (Tamil / உள்ளடக்கம்)</label>
          <textarea rows={10} value={form.content_ta} onChange={(e) => handleChange('content_ta', e.target.value)} placeholder="தமிழில் உள்ளடக்கம்" />
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
