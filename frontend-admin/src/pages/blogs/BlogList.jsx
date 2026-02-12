import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import SearchBar from '../../components/SearchBar';
import StatusBadge from '../../components/StatusBadge';
import ConfirmDialog from '../../components/ConfirmDialog';
import BlogForm from './BlogForm';

export default function BlogList() {
  const [blogs, setBlogs] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchBlogs = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (search) params.search = search;
      const res = await client.get('/blogs', { params });
      setBlogs(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load blogs'); }
    finally { setLoading(false); }
  }, [page, search]);

  useEffect(() => { fetchBlogs(); }, [fetchBlogs]);

  const handleDelete = async () => {
    try {
      await client.delete(`/blogs/${deleteId}`);
      toast.success('Blog deleted');
      setDeleteId(null);
      fetchBlogs();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => {
    setShowForm(false);
    setEditItem(null);
    fetchBlogs();
  };

  const columns = [
    { key: 'title', label: 'Title', render: (v) => <strong>{v}</strong> },
    { key: 'status', label: 'Status', width: '100px', render: (v) => <StatusBadge status={v} /> },
    { key: 'view_count', label: 'Views', width: '80px' },
    { key: 'created_at', label: 'Created', width: '120px', render: (v) => v ? new Date(v).toLocaleDateString() : '—' },
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
      <h1 className="page-title">Blog Posts</h1>
      <div className="page-toolbar">
        <SearchBar onSearch={(v) => { setSearch(v); setPage(1); }} placeholder="Search blogs..." />
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> New Post
        </button>
      </div>

      <DataTable
        columns={columns}
        data={blogs}
        loading={loading}
        pagination={pagination}
        onPageChange={setPage}
      />

      {showForm && (
        <BlogForm
          item={editItem}
          onClose={() => { setShowForm(false); setEditItem(null); }}
          onSaved={handleSaved}
        />
      )}

      <ConfirmDialog
        isOpen={!!deleteId}
        onClose={() => setDeleteId(null)}
        onConfirm={handleDelete}
        message="This blog post will be permanently deleted."
      />
    </div>
  );
}
