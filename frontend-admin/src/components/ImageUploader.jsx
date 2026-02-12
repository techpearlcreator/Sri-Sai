import { useState, useRef } from 'react';
import { HiOutlineUpload, HiOutlineX } from 'react-icons/hi';
import client from '../api/client';
import toast from 'react-hot-toast';

export default function ImageUploader({ value, onChange, module = 'general' }) {
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
      toast.success('Image uploaded');
    } catch (err) {
      toast.error(err.response?.data?.error?.message || 'Upload failed');
    } finally {
      setUploading(false);
      if (fileRef.current) fileRef.current.value = '';
    }
  };

  return (
    <div className="image-uploader">
      {value ? (
        <div className="image-uploader__preview">
          <img src={`${storageBase}${value}`} alt="Preview" />
          <button className="image-uploader__remove" onClick={() => onChange(null)} title="Remove">
            <HiOutlineX size={16} />
          </button>
        </div>
      ) : (
        <label className="image-uploader__dropzone">
          <HiOutlineUpload size={24} />
          <span>{uploading ? 'Uploading...' : 'Click to upload'}</span>
          <input ref={fileRef} type="file" accept="image/*" onChange={handleUpload} disabled={uploading} hidden />
        </label>
      )}
    </div>
  );
}
