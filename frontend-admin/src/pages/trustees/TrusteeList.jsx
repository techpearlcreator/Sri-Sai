import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import ConfirmDialog from '../../components/ConfirmDialog';
import TrusteeForm from './TrusteeForm';

export default function TrusteeList() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const res = await client.get('/trustees');
      setItems(res.data.data);
    } catch { toast.error('Failed to load trustees'); }
    finally { setLoading(false); }
  }, []);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleDelete = async () => {
    try {
      await client.delete(`/trustees/${deleteId}`);
      toast.success('Trustee deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => { setShowForm(false); setEditItem(null); fetchItems(); };

  const columns = [
    { key: 'display_order', label: '#', width: '50px' },
    { key: 'name', label: 'Name', render: (v) => <strong>{v}</strong> },
    { key: 'designation', label: 'Designation', width: '180px' },
    { key: 'trustee_type', label: 'Type', width: '120px', render: (v) => v === 'main' ? 'Main Trustee' : 'Co-opted' },
    { key: 'phone', label: 'Phone', width: '130px' },
    {
      key: 'id', label: 'Actions', width: '150px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); setEditItem(row); setShowForm(true); }}>Edit</button>
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>Delete</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">Trustees</h1>
      <div className="page-toolbar">
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> Add Trustee
        </button>
      </div>
      <DataTable columns={columns} data={items} loading={loading} />
      {showForm && <TrusteeForm item={editItem} onClose={() => { setShowForm(false); setEditItem(null); }} onSaved={handleSaved} />}
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message="This trustee will be permanently deleted." />
    </div>
  );
}
