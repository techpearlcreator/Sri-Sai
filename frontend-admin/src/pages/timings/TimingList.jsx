import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import ConfirmDialog from '../../components/ConfirmDialog';
import TimingForm from './TimingForm';
import { useLang } from '../../contexts/LangContext';

export default function TimingList() {
  const { t } = useLang();
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const res = await client.get('/temple-timings');
      setItems(res.data.data);
    } catch { toast.error('Failed to load timings'); }
    finally { setLoading(false); }
  }, []);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleDelete = async () => {
    try {
      await client.delete(`/temple-timings/${deleteId}`);
      toast.success('Timing deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => { setShowForm(false); setEditItem(null); fetchItems(); };

  const columns = [
    { key: 'title', label: 'Pooja / Event', render: (v) => <strong>{v}</strong> },
    { key: 'day_type', label: 'Day', width: '120px', render: (v) => (v || '').replace(/_/g, ' ') },
    { key: 'start_time', label: 'Start', width: '100px' },
    { key: 'end_time', label: 'End', width: '100px' },
    { key: 'location', label: t('table.temple'), width: '180px' },
    {
      key: 'id', label: t('table.actions'), width: '150px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); setEditItem(row); setShowForm(true); }}>{t('common.edit')}</button>
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>{t('common.delete')}</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">{t('pages.timings')}</h1>
      <div className="page-toolbar">
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> {t('btn.new_timing')}
        </button>
      </div>
      <DataTable columns={columns} data={items} loading={loading} />
      {showForm && <TimingForm item={editItem} onClose={() => { setShowForm(false); setEditItem(null); }} onSaved={handleSaved} />}
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message={t('confirm.delete_timing')} />
    </div>
  );
}
