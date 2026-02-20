import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import StatusBadge from '../../components/StatusBadge';
import { useLang } from '../../contexts/LangContext';

export default function TourBookingList() {
  const { t } = useLang();
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [paymentStatus, setPaymentStatus] = useState('');

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (paymentStatus) params.payment_status = paymentStatus;
      const res = await client.get('/tour-bookings', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load bookings'); }
    finally { setLoading(false); }
  }, [page, paymentStatus]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const cancelBooking = async (id) => {
    if (!window.confirm('Cancel this booking?')) return;
    try {
      await client.put(`/tour-bookings/${id}`, { status: 'cancelled' });
      toast.success('Booking cancelled');
      fetchItems();
    } catch { toast.error('Cancel failed'); }
  };

  const columns = [
    { key: 'user_name', label: t('table.user'), render: (v) => <strong>{v}</strong> },
    { key: 'user_phone', label: t('table.phone'), width: '120px' },
    { key: 'tour_title', label: t('sidebar.tours'), width: '180px' },
    { key: 'seats', label: t('table.seats'), width: '70px' },
    { key: 'total_amount', label: t('table.amount'), width: '100px', render: (v) => `₹${parseFloat(v).toFixed(2)}` },
    { key: 'payment_status', label: t('table.payment'), width: '100px', render: (v) => <StatusBadge status={v} /> },
    { key: 'status', label: t('table.status'), width: '100px', render: (v) => <StatusBadge status={v} /> },
    {
      key: 'id', label: t('common.actions'), width: '100px', render: (_, row) => (
        row.status !== 'cancelled' ? (
          <button className="btn btn-sm btn-outline" onClick={() => cancelBooking(row.id)}>{t('btn.cancel_booking')}</button>
        ) : <span style={{ color: '#999' }}>Cancelled</span>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">{t('pages.tour_bookings')}</h1>
      <div className="page-toolbar">
        <select value={paymentStatus} onChange={(e) => { setPaymentStatus(e.target.value); setPage(1); }} style={{ padding: '8px 12px', borderRadius: 6, border: '1px solid #ddd' }}>
          <option value="">{t('filter.all_payment_statuses')}</option>
          <option value="pending">Pending</option>
          <option value="paid">Paid</option>
          <option value="failed">Failed</option>
          <option value="refunded">Refunded</option>
        </select>
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />
    </div>
  );
}
