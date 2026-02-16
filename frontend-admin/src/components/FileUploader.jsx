import { useState, useRef } from 'react';
import { HiOutlineUpload, HiOutlineX, HiOutlineDocumentText } from 'react-icons/hi';
import client from '../api/client';
import toast from 'react-hot-toast';

export default function FileUploader({ value, onChange, module = 'general', accept = '.pdf', label = 'PDF' }) {
  const [uploading, setUploading] = useState(false);
  const fileRef = useRef();
  const apiBase = import.meta.env.VITE_API_URL || 'http://localhost/srisai/public/api/v1';
  const storageBase = apiBase.replace('/api/v1', '/storage/uploads/');

  const handleUpload = async (e) => {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('module', module);

    setUploading(true);
    try {
      const res = await client.post('/media/upload', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      const path = res.data.data.file_path;
      onChange(path);
      toast.success(`${label} uploaded`);
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Upload failed');
    } finally {
      setUploading(false);
      if (fileRef.current) fileRef.current.value = '';
    }
  };

  const fileName = value ? value.split('/').pop() : '';

  return (
    <div className="image-uploader">
      {value ? (
        <div className="image-uploader__preview" style={{ flexDirection: 'row', alignItems: 'center', gap: '0.75rem', padding: '0.75rem 1rem' }}>
          <HiOutlineDocumentText size={28} style={{ color: '#d32f2f', flexShrink: 0 }} />
          <a href={`${storageBase}${value}`} target="_blank" rel="noopener noreferrer"
            style={{ flex: 1, overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap', color: '#5F2C70', fontWeight: 500 }}>
            {fileName}
          </a>
          <button className="image-uploader__remove" onClick={() => onChange(null)} title="Remove" style={{ position: 'static' }}>
            <HiOutlineX size={16} />
          </button>
        </div>
      ) : (
        <label className="image-uploader__dropzone">
          <HiOutlineUpload size={24} />
          <span>{uploading ? 'Uploading...' : `Click to upload ${label}`}</span>
          <input ref={fileRef} type="file" accept={accept} onChange={handleUpload} disabled={uploading} hidden />
        </label>
      )}
    </div>
  );
}
