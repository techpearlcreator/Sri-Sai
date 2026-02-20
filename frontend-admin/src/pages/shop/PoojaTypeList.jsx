import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import ConfirmDialog from '../../components/ConfirmDialog';
import PoojaTypeForm from './PoojaTypeForm';
import { useLang } from '../../contexts/LangContext';

export default function PoojaTypeList() {
  const { t } = useLang();
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [temple, setTemple] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (temple) params.temple = temple;
      const res = await client.get('/pooja-types', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load pooja types'); }
    finally { setLoading(false); }
  }, [page, temple]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleDelete = async () => {
    try {
      await client.delete(`/pooja-types/${deleteId}`);
      toast.success('Pooja type deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => { setShowForm(false); setEditItem(null); fetchItems(); };

  const templeLabel = (v) => {
    const map = { perungalathur: 'Perungalathur', keerapakkam: 'Keerapakkam', both: 'Both' };
    return map[v] || v;
  };

  const columns = [
    { key: 'name', label: t('table.pooja'), render: (v) => <strong>{v}</strong> },
    { key: 'temple', label: t('table.temple'), width: '140px', render: templeLabel },
    { key: 'price', label: t('table.price'), width: '100px', render: (v) => `₹${parseFloat(v).toFixed(2)}` },
    { key: 'duration', label: t('table.duration'), width: '100px' },
    { key: 'is_active', label: t('common.active'), width: '80px', render: (v) => v ? t('common.yes') : t('common.no') },
    {
      key: 'id', label: t('common.actions'), width: '150px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); setEditItem(row); setShowForm(true); }}>{t('common.edit')}</button>
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>{t('common.delete')}</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">{t('pages.pooja_types')}</h1>
      <div className="page-toolbar">
        <select value={temple} onChange={(e) => { setTemple(e.target.value); setPage(1); }} style={{ padding: '8px 12px', borderRadius: 6, border: '1px solid #ddd' }}>
          <option value="">{t('filter.all_temples')}</option>
          <option value="perungalathur">Perungalathur</option>
          <option value="keerapakkam">Keerapakkam</option>
          <option value="both">Both</option>
        </select>
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> {t('btn.new_pooja_type')}
        </button>
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />
      {showForm && <PoojaTypeForm item={editItem} onClose={() => { setShowForm(false); setEditItem(null); }} onSaved={handleSaved} />}
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message={t('common.confirm_delete')} />
    </div>
  );
}
