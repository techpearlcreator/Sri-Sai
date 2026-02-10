import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import SearchBar from '../../components/SearchBar';
import StatusBadge from '../../components/StatusBadge';
import Modal from '../../components/Modal';
import ConfirmDialog from '../../components/ConfirmDialog';

export default function ContactList() {
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [viewItem, setViewItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (search) params.search = search;
      const res = await client.get('/contacts', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load messages'); }
    finally { setLoading(false); }
  }, [page, search]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleView = async (item) => {
    setViewItem(item);
    if (item.status === 'unread') {
      try {
        await client.put(`/contacts/${item.id}`, { status: 'read' });
        fetchItems();
      } catch {}
    }
  };

  const handleDelete = async () => {
    try {
      await client.delete(`/contacts/${deleteId}`);
      toast.success('Message deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const handleStatusChange = async (id, status) => {
    try {
      await client.put(`/contacts/${id}`, { status });
      toast.success('Status updated');
      setViewItem(null);
      fetchItems();
    } catch { toast.error('Update failed'); }
  };

  const columns = [
    { key: 'name', label: 'Name', render: (v, row) => (
      <strong style={{ fontWeight: row.status === 'unread' ? 700 : 400 }}>{v}</strong>
    )},
    { key: 'email', label: 'Email', width: '200px' },
    { key: 'subject', label: 'Subject' },
    { key: 'status', label: 'Status', width: '100px', render: (v) => <StatusBadge status={v} /> },
    { key: 'created_at', label: 'Received', width: '120px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
    {
      key: 'id', label: '', width: '130px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); handleView(row); }}>View</button>
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>Delete</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">Contact Messages</h1>
      <div className="page-toolbar">
        <SearchBar onSearch={(v) => { setSearch(v); setPage(1); }} placeholder="Search messages..." />
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />

      {viewItem && (
        <Modal isOpen onClose={() => setViewItem(null)} title="Message Details" wide>
          <div className="detail-grid">
            <div className="detail-row"><span className="detail-label">Name</span><span>{viewItem.name}</span></div>
            <div className="detail-row"><span className="detail-label">Email</span><span>{viewItem.email || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Phone</span><span>{viewItem.phone || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Subject</span><span>{viewItem.subject || '—'}</span></div>
            <div className="detail-row"><span className="detail-label">Status</span><span><StatusBadge status={viewItem.status} /></span></div>
            <div className="detail-row"><span className="detail-label">Received</span><span>{viewItem.created_at ? new Date(viewItem.created_at).toLocaleString() : '—'}</span></div>
          </div>
          <div style={{ marginTop: '1rem', padding: '1rem', background: '#f8f8f8', borderRadius: '8px', whiteSpace: 'pre-wrap' }}>
            {viewItem.message}
          </div>
          <div className="form-actions" style={{ marginTop: '1rem' }}>
            {viewItem.status !== 'replied' && (
              <button className="btn btn-sm btn-primary" onClick={() => handleStatusChange(viewItem.id, 'replied')}>
                Mark as Replied
              </button>
            )}
            {viewItem.status !== 'archived' && (
              <button className="btn btn-sm btn-outline" onClick={() => handleStatusChange(viewItem.id, 'archived')}>
                Archive
              </button>
            )}
          </div>
        </Modal>
      )}

      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message="This message will be permanently deleted." />
    </div>
  );
}
