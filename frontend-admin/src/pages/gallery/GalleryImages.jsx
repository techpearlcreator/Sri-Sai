import { useState, useEffect, useRef } from 'react';
import { HiOutlineArrowLeft, HiOutlineUpload, HiOutlineTrash } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import ConfirmDialog from '../../components/ConfirmDialog';

export default function GalleryImages({ album, onBack }) {
  const [images, setImages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [uploading, setUploading] = useState(false);
  const [deleteId, setDeleteId] = useState(null);
  const fileRef = useRef();
  const apiBase = import.meta.env.VITE_API_URL || 'http://localhost/srisai/public/api/v1';
  const storageBase = apiBase.replace('/api/v1', '/storage/uploads/');

  const fetchImages = async () => {
    setLoading(true);
    try {
      const res = await client.get(`/gallery/${album.id}`);
      setImages(res.data.data?.images || []);
    } catch { toast.error('Failed to load images'); }
    finally { setLoading(false); }
  };

  useEffect(() => { fetchImages(); }, [album.id]);

  const handleUpload = async (e) => {
    const files = Array.from(e.target.files);
    if (!files.length) return;
    setUploading(true);
    let uploaded = 0;
    for (const file of files) {
      try {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('caption', file.name.replace(/\.[^.]+$/, ''));
        await client.post(`/gallery/${album.id}/images`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        });
        uploaded++;
      } catch { toast.error(`Failed to upload ${file.name}`); }
    }
    if (uploaded > 0) toast.success(`${uploaded} image(s) uploaded`);
    setUploading(false);
    if (fileRef.current) fileRef.current.value = '';
    fetchImages();
  };

  const handleDelete = async () => {
    try {
      await client.delete(`/gallery/images/${deleteId}`);
      toast.success('Image deleted');
      setDeleteId(null);
      fetchImages();
    } catch { toast.error('Delete failed'); }
  };

  return (
    <div>
      <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', marginBottom: '1.5rem' }}>
        <button className="btn btn-outline" onClick={onBack}>
          <HiOutlineArrowLeft size={18} /> Back to Albums
        </button>
        <h1 className="page-title" style={{ marginBottom: 0 }}>{album.title} — Images</h1>
      </div>

      <div className="page-toolbar">
        <label className="btn btn-primary" style={{ cursor: 'pointer' }}>
          <HiOutlineUpload size={18} /> {uploading ? 'Uploading...' : 'Upload Images'}
          <input ref={fileRef} type="file" accept="image/*" multiple onChange={handleUpload} disabled={uploading} hidden />
        </label>
        <span style={{ fontSize: '0.85rem', color: '#666' }}>{images.length} image(s)</span>
      </div>

      {loading ? (
        <div className="loading"><div className="spinner" style={{ margin: '0 auto' }} /></div>
      ) : images.length === 0 ? (
        <p style={{ color: '#666', padding: '2rem 0' }}>No images yet. Upload some!</p>
      ) : (
        <div className="gallery-grid">
          {images.map((img) => (
            <div key={img.id} className="gallery-grid__item">
              <img src={`${storageBase}${img.file_path}`} alt={img.caption || ''} />
              <div className="gallery-grid__overlay">
                <span className="gallery-grid__caption">{img.caption || ''}</span>
                <button className="gallery-grid__delete" onClick={() => setDeleteId(img.id)} title="Delete">
                  <HiOutlineTrash size={16} />
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message="This image will be permanently deleted." />
    </div>
  );
}
