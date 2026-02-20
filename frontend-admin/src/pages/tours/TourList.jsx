import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import StatusBadge from '../../components/StatusBadge';
import ConfirmDialog from '../../components/ConfirmDialog';
import TourForm from './TourForm';
import { useLang } from '../../contexts/LangContext';

export default function TourList() {
  const { t } = useLang();
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [status, setStatus] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (status) params.status = status;
      const res = await client.get('/tours', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load tours'); }
    finally { setLoading(false); }
  }, [page, status]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleDelete = async () => {
    try {
      await client.delete(`/tours/${deleteId}`);
      toast.success('Tour deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => { setShowForm(false); setEditItem(null); fetchItems(); };

  const columns = [
    { key: 'title', label: t('table.title'), render: (v) => <strong>{v}</strong> },
    { key: 'destination', label: t('table.destination'), width: '150px' },
    { key: 'start_date', label: t('table.date'), width: '110px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
    { key: 'price_per_person', label: t('table.price'), width: '110px', render: (v) => `₹${parseFloat(v).toFixed(2)}` },
    { key: 'booked_seats', label: t('table.booked'), width: '90px', render: (v, row) => `${v}/${row.max_seats}` },
    { key: 'status', label: t('table.status'), width: '100px', render: (v) => <StatusBadge status={v} /> },
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
      <h1 className="page-title">{t('pages.tours')}</h1>
      <div className="page-toolbar">
        <select value={status} onChange={(e) => { setStatus(e.target.value); setPage(1); }} style={{ padding: '8px 12px', borderRadius: 6, border: '1px solid #ddd' }}>
          <option value="">{t('filter.all_statuses')}</option>
          <option value="upcoming">Upcoming</option>
          <option value="ongoing">Ongoing</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> {t('btn.new_tour')}
        </button>
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />
      {showForm && <TourForm item={editItem} onClose={() => { setShowForm(false); setEditItem(null); }} onSaved={handleSaved} />}
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message={t('common.confirm_delete')} />
    </div>
  );
}
