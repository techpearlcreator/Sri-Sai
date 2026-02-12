import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];

export default function MagazineForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', description: '', cover_image: null, pdf_file: null,
    issue_number: '', issue_month: '', issue_year: new Date().getFullYear(),
    status: 'draft',
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/magazines/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          title: d.title || '', description: d.description || '',
          cover_image: d.cover_image || null, pdf_file: d.pdf_file || null,
          issue_number: d.issue_number || '', issue_month: d.issue_month || '',
          issue_year: d.issue_year || new Date().getFullYear(), status: d.status || 'draft',
        });
      }).catch(() => toast.error('Failed to load magazine'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      if (isEdit) {
        await client.put(`/magazines/${item.id}`, form);
        toast.success('Magazine updated');
      } else {
        await client.post('/magazines', form);
        toast.success('Magazine created');
      }
      onSaved();
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Magazine' : 'New Magazine Issue'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Title</label>
          <input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={3} />
        </div>

        <div className="form-row">
          <div className="form-group">
            <label>Issue Number</label>
            <input type="number" value={form.issue_number} onChange={(e) => handleChange('issue_number', e.target.value)} />
          </div>
          <div className="form-group">
            <label>Month</label>
            <select value={form.issue_month} onChange={(e) => handleChange('issue_month', e.target.value)}>
              <option value="">— Select —</option>
              {MONTHS.map((m) => <option key={m} value={m}>{m}</option>)}
            </select>
          </div>
          <div className="form-group">
            <label>Year</label>
            <input type="number" value={form.issue_year} onChange={(e) => handleChange('issue_year', e.target.value)} min={2000} max={2099} />
          </div>
        </div>

        <div className="form-group">
          <label>Status</label>
          <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
            <option value="archived">Archived</option>
          </select>
        </div>

        <div className="form-group">
          <label>Description</label>
          <textarea rows={4} value={form.description} onChange={(e) => handleChange('description', e.target.value)} />
        </div>

        <div className="form-group">
          <label>Cover Image</label>
          <ImageUploader value={form.cover_image} onChange={(v) => handleChange('cover_image', v)} module="magazines" />
        </div>

        <div className="form-group">
          <label>PDF File Path</label>
          <input value={form.pdf_file || ''} onChange={(e) => handleChange('pdf_file', e.target.value)} placeholder="Upload via Media, then paste path" />
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
