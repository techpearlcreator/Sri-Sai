import { useState, useEffect, useCallback, useRef } from 'react';
import { HiOutlineUpload, HiOutlineTrash } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import ConfirmDialog from '../../components/ConfirmDialog';

export default function MediaLibrary() {
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [uploading, setUploading] = useState(false);
  const [deleteId, setDeleteId] = useState(null);
  const fileRef = useRef();
  const apiBase = import.meta.env.VITE_API_URL || 'http://localhost/srisai/public/api/v1';
  const storageBase = apiBase.replace('/api/v1', '/storage/uploads/');

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const res = await client.get('/media', { params: { page, per_page: 24 } });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load media'); }
    finally { setLoading(false); }
  }, [page]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleUpload = async (e) => {
    const files = Array.from(e.target.files);
    if (!files.length) return;
    setUploading(true);
    let uploaded = 0;
    for (const file of files) {
      try {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('module', 'general');
        await client.post('/media/upload', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });
        uploaded++;
      } catch { toast.error(`Failed: ${file.name}`); }
    }
    if (uploaded > 0) toast.success(`${uploaded} file(s) uploaded`);
    setUploading(false);
    if (fileRef.current) fileRef.current.value = '';
    fetchItems();
  };

  const handleDelete = async () => {
    try {
      await client.delete(`/media/${deleteId}`);
      toast.success('File deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const isImage = (path) => /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(path);

  return (
    <div>
      <h1 className="page-title">Media Library</h1>
      <div className="page-toolbar">
        <label className="btn btn-primary" style={{ cursor: 'pointer' }}>
          <HiOutlineUpload size={18} /> {uploading ? 'Uploading...' : 'Upload Files'}
          <input ref={fileRef} type="file" multiple onChange={handleUpload} disabled={uploading} hidden />
        </label>
        <span style={{ fontSize: '0.85rem', color: '#666' }}>
          {pagination ? `${pagination.total} total files` : ''}
        </span>
      </div>

      {loading ? (
        <div className="loading"><div className="spinner" style={{ margin: '0 auto' }} /></div>
      ) : items.length === 0 ? (
        <p style={{ color: '#666', padding: '2rem 0' }}>No media files yet.</p>
      ) : (
        <div className="media-grid">
          {items.map((item) => (
            <div key={item.id} className="media-grid__item">
              {isImage(item.file_path) ? (
                <img src={`${storageBase}${item.file_path}`} alt={item.original_name || ''} />
              ) : (
                <div className="media-grid__file">{(item.file_path || '').split('.').pop().toUpperCase()}</div>
              )}
              <div className="media-grid__info">
                <span className="media-grid__name" title={item.original_name}>{item.original_name || item.file_path}</span>
                <span className="media-grid__size">{item.file_size ? `${(item.file_size / 1024).toFixed(0)} KB` : ''}</span>
              </div>
              <button className="media-grid__delete" onClick={() => setDeleteId(item.id)} title="Delete">
                <HiOutlineTrash size={14} />
              </button>
            </div>
          ))}
        </div>
      )}

      {pagination && pagination.total_pages > 1 && (
        <div className="pagination">
          <button className="btn btn-sm btn-outline" disabled={page <= 1} onClick={() => setPage(page - 1)}>Prev</button>
          <span className="pagination__info">Page {page} of {pagination.total_pages}</span>
          <button className="btn btn-sm btn-outline" disabled={page >= pagination.total_pages} onClick={() => setPage(page + 1)}>Next</button>
        </div>
      )}

      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message="This file will be permanently deleted." />
    </div>
  );
}
