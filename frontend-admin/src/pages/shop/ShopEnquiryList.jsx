import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import StatusBadge from '../../components/StatusBadge';
import ConfirmDialog from '../../components/ConfirmDialog';
import { useLang } from '../../contexts/LangContext';

export default function ShopEnquiryList() {
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
      const res = await client.get('/shop-enquiries', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load enquiries'); }
    finally { setLoading(false); }
  }, [page, status]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const updateStatus = async (id, newStatus) => {
    try {
      await client.put(`/shop-enquiries/${id}`, { status: newStatus });
      toast.success('Status updated');
      fetchItems();
    } catch { toast.error('Update failed'); }
  };

  const handleDelete = async () => {
    try {
      await client.delete(`/shop-enquiries/${deleteId}`);
      toast.success('Enquiry deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const columns = [
    { key: 'name', label: t('table.name'), render: (v) => <strong>{v}</strong> },
    { key: 'phone', label: t('table.phone'), width: '120px' },
    { key: 'product_name', label: t('table.product'), width: '160px' },
    { key: 'quantity', label: t('table.quantity'), width: '60px' },
    { key: 'created_at', label: t('table.date'), width: '110px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
    { key: 'status', label: t('table.status'), width: '110px', render: (v) => <StatusBadge status={v} /> },
    {
      key: 'id', label: t('common.actions'), width: '200px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap' }}>
          {row.status === 'new' && <button className="btn btn-sm btn-primary" onClick={() => updateStatus(row.id, 'contacted')}>{t('btn.contacted')}</button>}
          {row.status === 'contacted' && <button className="btn btn-sm btn-primary" onClick={() => updateStatus(row.id, 'completed')}>{t('btn.complete')}</button>}
          {row.status !== 'cancelled' && <button className="btn btn-sm btn-outline" onClick={() => updateStatus(row.id, 'cancelled')}>{t('btn.cancel_booking')}</button>}
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>{t('common.delete')}</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">{t('pages.shop_enquiries')}</h1>
      <div className="page-toolbar">
        <select value={status} onChange={(e) => { setStatus(e.target.value); setPage(1); }} style={{ padding: '8px 12px', borderRadius: 6, border: '1px solid #ddd' }}>
          <option value="">{t('filter.all_statuses')}</option>
          <option value="new">New</option>
          <option value="contacted">Contacted</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message={t('common.confirm_delete')} />
    </div>
  );
}
