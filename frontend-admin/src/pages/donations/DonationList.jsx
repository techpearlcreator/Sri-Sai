import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import SearchBar from '../../components/SearchBar';
import StatusBadge from '../../components/StatusBadge';
import Modal from '../../components/Modal';

export default function DonationList() {
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [viewItem, setViewItem] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (search) params.search = search;
      const res = await client.get('/donations', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load donations'); }
    finally { setLoading(false); }
  }, [page, search]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const columns = [
    { key: 'donor_name', label: 'Donor', render: (v) => <strong>{v}</strong> },
    { key: 'amount', label: 'Amount', width: '120px', render: (v) => `₹${Number(v).toLocaleString('en-IN')}` },
    { key: 'donation_type', label: 'Type', width: '120px', render: (v) => (v || '').replace(/_/g, ' ') },
    { key: 'payment_method', label: 'Payment', width: '100px' },
    { key: 'status', label: 'Status', width: '100px', render: (v) => <StatusBadge status={v} /> },
    { key: 'donation_date', label: 'Date', width: '120px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
    {
      key: 'id', label: '', width: '80px', render: (_, row) => (
        <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); setViewItem(row); }}>View</button>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">Donations</h1>
      <div className="page-toolbar">
        <SearchBar onSearch={(v) => { setSearch(v); setPage(1); }} placeholder="Search donations..." />
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />

      {viewItem && (
        <Modal isOpen onClose={() => setViewItem(null)} title="Donation Details">
          <div className="detail-grid">
            <div className="detail-row"><span className="detail-label">Donor</span><span>{viewItem.donor_name}</span></div>
            <div className="detail-row"><span className="detail-label">Email</span><span>{viewItem.donor_email || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Phone</span><span>{viewItem.donor_phone || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Amount</span><span>₹{Number(viewItem.amount).toLocaleString('en-IN')}</span></div>
            <div className="detail-row"><span className="detail-label">Type</span><span>{(viewItem.donation_type || '').replace(/_/g, ' ')}</span></div>
            <div className="detail-row"><span className="detail-label">Payment</span><span>{viewItem.payment_method || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Transaction ID</span><span>{viewItem.transaction_id || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Receipt No</span><span>{viewItem.receipt_number || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Status</span><span><StatusBadge status={viewItem.status} /></span></div>
            <div className="detail-row"><span className="detail-label">Date</span><span>{viewItem.donation_date ? new Date(viewItem.donation_date).toLocaleDateString() : '—'}</span></div>
            {viewItem.notes && <div className="detail-row"><span className="detail-label">Notes</span><span>{viewItem.notes}</span></div>}
          </div>
        </Modal>
      )}
    </div>
  );
}
