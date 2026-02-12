import { HiChevronLeft, HiChevronRight } from 'react-icons/hi';

export default function DataTable({ columns, data, loading, pagination, onPageChange, onRowClick, emptyMessage = 'No records found' }) {
  if (loading) return <div className="loading">Loading...</div>;

  return (
    <div className="data-table-wrapper">
      <table className="data-table">
        <thead>
          <tr>
            {columns.map((col) => (
              <th key={col.key} style={col.width ? { width: col.width } : {}}>
                {col.label}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {data.length === 0 ? (
            <tr><td colSpan={columns.length} className="data-table__empty">{emptyMessage}</td></tr>
          ) : (
            data.map((row, idx) => (
              <tr key={row.id || idx} onClick={() => onRowClick?.(row)} className={onRowClick ? 'data-table__row--clickable' : ''}>
                {columns.map((col) => (
                  <td key={col.key}>
                    {col.render ? col.render(row[col.key], row) : row[col.key] ?? '—'}
                  </td>
                ))}
              </tr>
            ))
          )}
        </tbody>
      </table>

      {pagination && pagination.last_page > 1 && (
        <div className="pagination">
          <button
            className="btn btn-sm btn-outline"
            disabled={pagination.current_page <= 1}
            onClick={() => onPageChange(pagination.current_page - 1)}
          >
            <HiChevronLeft size={16} />
          </button>
          <span className="pagination__info">
            Page {pagination.current_page} of {pagination.last_page} ({pagination.total} total)
          </span>
          <button
            className="btn btn-sm btn-outline"
            disabled={pagination.current_page >= pagination.last_page}
            onClick={() => onPageChange(pagination.current_page + 1)}
          >
            <HiChevronRight size={16} />
          </button>
        </div>
      )}
    </div>
  );
}
