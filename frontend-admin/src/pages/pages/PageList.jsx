import { useState, useEffect, useCallback } from 'react';
import { HiOutlinePlus } from 'react-icons/hi';
import client from '../../api/client';
import toast from 'react-hot-toast';
import DataTable from '../../components/DataTable';
import SearchBar from '../../components/SearchBar';
import StatusBadge from '../../components/StatusBadge';
import ConfirmDialog from '../../components/ConfirmDialog';
import PageForm from './PageForm';
import { useLang } from '../../contexts/LangContext';

export default function PageList() {
  const { t } = useLang();
  const [items, setItems] = useState([]);
  const [pagination, setPagination] = useState(null);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [search, setSearch] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const fetchItems = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: 15 };
      if (search) params.search = search;
      const res = await client.get('/pages', { params });
      setItems(res.data.data);
      setPagination(res.data.meta);
    } catch { toast.error('Failed to load pages'); }
    finally { setLoading(false); }
  }, [page, search]);

  useEffect(() => { fetchItems(); }, [fetchItems]);

  const handleDelete = async () => {
    try {
      await client.delete(`/pages/${deleteId}`);
      toast.success('Page deleted');
      setDeleteId(null);
      fetchItems();
    } catch { toast.error('Delete failed'); }
  };

  const handleSaved = () => { setShowForm(false); setEditItem(null); fetchItems(); };

  const columns = [
    { key: 'title', label: t('table.title'), render: (v) => <strong>{v}</strong> },
    { key: 'slug', label: 'Slug', width: '180px', render: (v) => <code style={{ fontSize: '0.8rem' }}>/{v}</code> },
    { key: 'menu_position', label: 'Menu Pos', width: '90px' },
    { key: 'status', label: t('table.status'), width: '100px', render: (v) => <StatusBadge status={v} /> },
    {
      key: 'id', label: t('table.actions'), width: '150px', render: (_, row) => (
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <button className="btn btn-sm btn-outline" onClick={(e) => { e.stopPropagation(); setEditItem(row); setShowForm(true); }}>{t('common.edit')}</button>
          <button className="btn btn-sm btn-danger" onClick={(e) => { e.stopPropagation(); setDeleteId(row.id); }}>{t('common.delete')}</button>
        </div>
      )
    },
  ];

  return (
    <div>
      <h1 className="page-title">{t('pages.pages')}</h1>
      <div className="page-toolbar">
        <SearchBar onSearch={(v) => { setSearch(v); setPage(1); }} placeholder={t('btn.search_pages')} />
        <div className="page-toolbar__spacer" />
        <button className="btn btn-primary" onClick={() => { setEditItem(null); setShowForm(true); }}>
          <HiOutlinePlus size={18} /> {t('btn.new_page')}
        </button>
      </div>
      <DataTable columns={columns} data={items} loading={loading} pagination={pagination} onPageChange={setPage} />
      {showForm && <PageForm item={editItem} onClose={() => { setShowForm(false); setEditItem(null); }} onSaved={handleSaved} />}
      <ConfirmDialog isOpen={!!deleteId} onClose={() => setDeleteId(null)} onConfirm={handleDelete} message={t('confirm.delete_page')} />
    </div>
  );
}
