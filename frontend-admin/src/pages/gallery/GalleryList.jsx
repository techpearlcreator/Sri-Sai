import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus, HiOutlinePhotograph } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import SearchBar from '../../components/SearchBar';
import StatusBadge from '../../components/StatusBadge';
import ConfirmDialog from '../../components/ConfirmDialog';
import GalleryForm from './GalleryForm';
import GalleryImages from './GalleryImages';

export default function GalleryList() {
  const [albums, setAlbums] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);
  const [manageAlbum, setManageAlbum] = useState(null);

  const fetchAlbums = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (search) params.search = search;
      const res = await client.get('/gallery', { params });
      setAlbums(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load albums'); }
    finally { setLoading(false); }
  }, [page, search]);

  useEffect(() => { fetchAlbums(); }, [fetchAlbums]);

  const handleDelete = async () => {
    try {
      await client.delete(`/gallery/${deleteId}`);
      toast.success('Album deleted');
      setDeleteId(null);
      fetchAlbums();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => { setShowForm(false); setEditItem(null); fetchAlbums(); };

  const columns = [
    { key: 'title', label: 'Album', render: (v) => <strong>{v}</strong> },
    { key: 'image_count', label: 'Images', width: '80px', render: (v) => v || 0 },
    { key: 'status', label: 'Status', width: '100px', render: (v) => <StatusBadge status={v} /> },
    { key: 'created_at', label: 'Created', width: '120px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
    {
      key: 'id', label: 'Actions', width: '220px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button className="btn btn-sm btn-primary" onClick={(e) => { e.stopPropagation(); setManageAlbum(row); }}>
            <HiOutlinePhotograph size={14} /> Images
          </button>
          <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); setEditItem(row); setShowForm(true); }}>Edit</button>
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>Delete</button>
        </div>
      )
    },
  ];

  if (manageAlbum) {
    return <GalleryImages album={manageAlbum} onBack={() => { setManageAlbum(null); fetchAlbums(); }} />;
  }

  return (
    <div>
      <h1 className="page-title">Gallery Albums</h1>
      <div className="page-toolbar">
        <SearchBar onSearch={(v) => { setSearch(v); setPage(1); }} placeholder="Search albums..." />
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> New Album
        </button>
      </div>
      <DataTable columns={columns} data={albums} loading={loading} pagination={pagination} onPageChange={setPage} />
      {showForm && <GalleryForm item={editItem} onClose={() => { setShowForm(false); setEditItem(null); }} onSaved={handleSaved} />}
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message="This album and all its images will be permanently deleted." />
    </div>
  );
}
