const colors = {
  published: { bg: '#d5f5e3', color: '#27ae60' },
  draft: { bg: '#fdebd0', color: '#e67e22' },
  archived: { bg: '#d6dbdf', color: '#566573' },
  upcoming: { bg: '#d6eaf8', color: '#2980b9' },
  ongoing: { bg: '#d5f5e3', color: '#27ae60' },
  completed: { bg: '#d6dbdf', color: '#566573' },
  cancelled: { bg: '#fadbd8', color: '#e74c3c' },
  unread: { bg: '#fadbd8', color: '#e74c3c' },
  read: { bg: '#d6eaf8', color: '#2980b9' },
  replied: { bg: '#d5f5e3', color: '#27ae60' },
  pending: { bg: '#fdebd0', color: '#e67e22' },
  failed: { bg: '#fadbd8', color: '#e74c3c' },
  refunded: { bg: '#e8daef', color: '#8e44ad' },
};

export default function StatusBadge({ status }) {
  const style = colors[status] || { bg: '#eee', color: '#666' };
  return (
    <span className="status-badge" style={{ background: style.bg, color: style.color }}>
      {status}
    </span>
  );
}
