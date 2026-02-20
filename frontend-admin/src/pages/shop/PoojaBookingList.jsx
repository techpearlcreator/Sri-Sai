import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import StatusBadge from '../../components/StatusBadge';
import ConfirmDialog from '../../components/ConfirmDialog';
import { useLang } from '../../contexts/LangContext';

export default function PoojaBookingList() {
  const { t } = useLang();
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [status, setStatus] = useState('');
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (status) params.status = status;
      const res = await client.get('/pooja-bookings', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load bookings'); }
    finally { setLoading(false); }
  }, [page, status]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const updateStatus = async (id, newStatus) => {
    try {
      await client.put(`/pooja-bookings/${id}`, { status: newStatus });
      toast.success('Status updated');
      fetchItems();
    } catch { toast.error('Update failed'); }
  };

  const handleDelete = async () => {
    try {
      await client.delete(`/pooja-bookings/${deleteId}`);
      toast.success('Booking deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const paymentBadge = (v) => {
    const map = { paid: { color: '#22c55e', bg: '#f0fdf4' }, failed: { color: '#ef4444', bg: '#fef2f2' }, pending: { color: '#f59e0b', bg: '#fffbeb' } };
    const s = map[v] || map.pending;
    return <span style={{ background: s.bg, color: s.color, padding: '2px 8px', borderRadius: 12, fontSize: '0.78rem', fontWeight: 600, textTransform: 'capitalize' }}>{v || 'pending'}</span>;
  };

  const columns = [
    { key: 'name', label: t('table.name'), render: (v) => <strong>{v}</strong> },
    { key: 'phone', label: t('table.phone'), width: '120px' },
    { key: 'pooja_name', label: t('table.pooja'), width: '150px' },
    { key: 'temple', label: t('table.temple'), width: '130px', render: (v) => v === 'perungalathur' ? 'Perungalathur' : 'Keerapakkam' },
    { key: 'num_persons', label: 'Persons', width: '80px', render: (v) => v || 1 },
    { key: 'preferred_date', label: t('table.date'), width: '110px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
    { key: 'payment_status', label: 'Payment', width: '100px', render: paymentBadge },
    { key: 'status', label: t('table.status'), width: '120px', render: (v) => <StatusBadge status={v} /> },
    {
      key: 'id', label: t('common.actions'), width: '200px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
          {row.status === 'pending' && <button className="btn btn-sm btn-primary" onClick={() => updateStatus(row.id, 'confirmed')}>{t('btn.confirm')}</button>}
          {row.status === 'confirmed' && <button className="btn btn-sm btn-primary" onClick={() => updateStatus(row.id, 'completed')}>{t('btn.complete')}</button>}
          {row.status !== 'cancelled' && <button className="btn btn-sm btn-outline" onClick={() => updateStatus(row.id, 'cancelled')}>{t('btn.cancel_booking')}</button>}
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>{t('common.delete')}</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">{t('pages.pooja_bookings')}</h1>
      <div className="page-toolbar">
        <select value={status} onChange={(e) => { setStatus(e.target.value); setPage(1); }} style={{ padding: '8px 12px', borderRadius: 6, border: '1px solid #ddd' }}>
          <option value="">{t('filter.all_statuses')}</option>
          <option value="pending">Pending</option>
          <option value="confirmed">Confirmed</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message={t('common.confirm_delete')} />
    </div>
  );
}
