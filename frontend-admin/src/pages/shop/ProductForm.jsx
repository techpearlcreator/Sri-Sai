import { useState, useEffect } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';
import ImageUploader from '../../components/ImageUploader';

export default function ProductForm({ item, onClose, onSaved }) {
  const [form, setForm] = useState({
    name: '', name_ta: '', category: 'book', description: '', description_ta: '',
    price: '', featured_image: null, stock_qty: 0, is_active: true, sort_order: 0,
  });
  const [saving, setSaving] = useState(false);
  const isEdit = !!item;

  useEffect(() => {
    if (item) {
      client.get(`/products/${item.id}`).then((res) => {
        const d = res.data.data;
        setForm({
          name: d.name || '', name_ta: d.name_ta || '',
          category: d.category || 'book',
          description: d.description || '', description_ta: d.description_ta || '',
          price: d.price || '', featured_image: d.featured_image || null,
          stock_qty: d.stock_qty || 0, is_active: !!d.is_active, sort_order: d.sort_order || 0,
        });
      }).catch(() => toast.error('Failed to load product'));
    }
  }, [item]);

  const handleChange = (key, value) => setForm((prev) => ({ ...prev, [key]: value }));

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      const payload = { ...form, is_active: form.is_active ? 1 : 0 };
      if (isEdit) { await client.put(`/products/${item.id}`, payload); toast.success('Product updated'); }
      else { await client.post('/products', payload); toast.success('Product created'); }
      onSaved();
    } catch (err) { toast.error(err.response?.data?.error?.message || 'Save failed'); }
    finally { setSaving(false); }
  };

  return (
    <Modal isOpen onClose={onClose} title={isEdit ? 'Edit Product' : 'New Product'} wide>
      <form onSubmit={handleSubmit}>
        <div className="form-row">
          <div className="form-group"><label>Category</label>
            <select value={form.category} onChange={(e) => handleChange('category', e.target.value)}>
              <option value="book">Book</option><option value="pooja_item">Pooja Item</option><option value="other">Other</option>
            </select>
          </div>
          <div className="form-group"><label>Price (₹) *</label><input type="number" step="0.01" min="0" value={form.price} onChange={(e) => handleChange('price', e.target.value)} required /></div>
          <div className="form-group"><label>Stock Qty</label><input type="number" min="0" value={form.stock_qty} onChange={(e) => handleChange('stock_qty', parseInt(e.target.value) || 0)} /></div>
          <div className="form-group"><label>Sort Order</label><input type="number" value={form.sort_order} onChange={(e) => handleChange('sort_order', parseInt(e.target.value) || 0)} /></div>
        </div>
        <div className="bilingual-grid">
          <div className="bilingual-col">
            <div className="bilingual-col-header col-en">🇬🇧 English</div>
            <div className="form-group"><label>Product Name *</label><input value={form.name} onChange={(e) => handleChange('name', e.target.value)} required minLength={2} /></div>
            <div className="form-group"><label>Description</label><textarea rows={5} value={form.description} onChange={(e) => handleChange('description', e.target.value)} /></div>
          </div>
          <div className="bilingual-col">
            <div className="bilingual-col-header col-ta">🇮🇳 தமிழ்</div>
            <div className="form-group"><label>பொருள் பெயர் (Name)</label><input value={form.name_ta} onChange={(e) => handleChange('name_ta', e.target.value)} placeholder="தமிழில் பொருள் பெயர்" /></div>
            <div className="form-group"><label>விளக்கம் (Description)</label><textarea rows={5} value={form.description_ta} onChange={(e) => handleChange('description_ta', e.target.value)} placeholder="தமிழில் விளக்கம்" /></div>
          </div>
        </div>
        <div className="form-group"><label>Product Image</label><ImageUploader value={form.featured_image} onChange={(v) => handleChange('featured_image', v)} module="products" /></div>
        <div className="form-group"><label style={{ display: 'flex', alignItems: 'center', gap: '0.5rem' }}><input type="checkbox" checked={form.is_active} onChange={(e) => handleChange('is_active', e.target.checked)} />Active (visible on website)</label></div>
        <div className="form-actions">
          <button type="button" className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button type="submit" className="btn btn-primary" disabled={saving}>{saving ? 'Saving...' : isEdit ? 'Update' : 'Create'}</button>
        </div>
      </form>
    </Modal>
  );
}
