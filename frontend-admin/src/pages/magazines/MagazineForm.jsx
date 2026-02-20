import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';
import FileUploader from '../../components/FileUploader';

const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];

export default function MagazineForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    title: '', title_ta: '', content: '', content_ta: '',
    featured_image: null, pdf_file: null,
    issue_number: '', issue_month: new Date().getMonth(), issue_year: new Date().getFullYear(),
    status: 'draft',
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/magazines/${item.id}`).then((res) => {
        const d = res.data.data;
        let month = new Date().getMonth(), year = new Date().getFullYear();
        if (d.issue_date) { const p = d.issue_date.split('-'); year = parseInt(p[0]) || year; month = (parseInt(p[1]) || 1) - 1; }
        setForm({
          title: d.title || '', title_ta: d.title_ta || '',
          content: d.content || '', content_ta: d.content_ta || '',
          featured_image: d.featured_image || null, pdf_file: d.pdf_file || null,
          issue_number: d.issue_number || '', issue_month: month, issue_year: year, status: d.status || 'draft',
        });
      }).catch(() => toast.error('Failed to load magazine'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const monthNum = String(parseInt(form.issue_month) + 1).padStart(2, '0');
      const payload = {
        title: form.title, title_ta: form.title_ta, content: form.content || '', content_ta: form.content_ta || '',
        featured_image: form.featured_image, pdf_file: form.pdf_file,
        issue_number: form.issue_number, issue_date: `${form.issue_year}-${monthNum}-01`, status: form.status,
      };
      if (isEdit) { await client.put(`/magazines/${item.id}`, payload); toast.success('Magazine updated'); }
      else { await client.post('/magazines', payload); toast.success('Magazine created'); }
      onSaved();
    } catch (err) {
      const details = err.response?.data?.error?.details;
      if (details) toast.error(Object.values(details).flat().join(', ') || 'Validation failed');
      else toast.error(err.response?.data?.error?.message || 'Save failed');
    } finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Magazine' : 'New Magazine Issue'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group"><label>Issue Number</label><input value={form.issue_number} onChange={(e) => handleChange('issue_number', e.target.value)} /></div>
          <div className="form-group"><label>Month</label>
            <select value={form.issue_month} onChange={(e) => handleChange('issue_month', parseInt(e.target.value))}>
              {MONTHS.map((m, i) => <option key={m} value={i}>{m}</option>)}
            </select>
          </div>
          <div className="form-group"><label>Year</label><input type="number" value={form.issue_year} onChange={(e) => handleChange('issue_year', e.target.value)} min={2000} max={2099} /></div>
          <div className="form-group"><label>Status</label>
            <select value={form.status} onChange={(e) => handleChange('status', e.target.value)}>
              <option value="draft">Draft</option><option value="published">Published</option><option value="archived">Archived</option>
            </select>
          </div>
        </div>
        <div className="bilingual-grid">
          <div className="bilingual-col">
            <div className="bilingual-col-header col-en">🇬🇧 English</div>
            <div className="form-group"><label>Title *</label><input value={form.title} onChange={(e) => handleChange('title', e.target.value)} required minLength={3} placeholder="Magazine title (min 3 characters)" /></div>
            <div className="form-group"><label>Description / Content</label><textarea rows={6} value={form.content} onChange={(e) => handleChange('content', e.target.value)} placeholder="Magazine description or content" /></div>
          </div>
          <div className="bilingual-col">
            <div className="bilingual-col-header col-ta">🇮🇳 தமிழ்</div>
            <div className="form-group"><label>தலைப்பு (Title)</label><input value={form.title_ta} onChange={(e) => handleChange('title_ta', e.target.value)} placeholder="தமிழில் தலைப்பு" /></div>
            <div className="form-group"><label>உள்ளடக்கம் (Content)</label><textarea rows={6} value={form.content_ta} onChange={(e) => handleChange('content_ta', e.target.value)} placeholder="தமிழில் உள்ளடக்கம்" /></div>
          </div>
        </div>
        <div className="form-row">
          <div className="form-group"><label>Cover Image</label><ImageUploader value={form.featured_image} onChange={(v) => handleChange('featured_image', v)} module="magazines" /></div>
          <div className="form-group"><label>PDF File</label><FileUploader value={form.pdf_file} onChange={(v) => handleChange('pdf_file', v)} module="magazines" accept=".pdf" label="PDF" /></div>
        </div>
        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>{saving ? 'Saving...' : isEdit ? 'Update' : 'Create'}</button>
        </div>
      </form>
    </Modal>
  );
}
