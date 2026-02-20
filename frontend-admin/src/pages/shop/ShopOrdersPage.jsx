import { useState, useEffect, useCallback } from 'react';
import client from '../../api/client';
import toast from 'react-hot-toast';
import Modal from '../../components/Modal';

const ORDER_STATUSES  = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
const PAYMENT_STATUSES = ['pending', 'paid', 'failed', 'refunded'];

function OrderDetailModal({ order, onClose, onUpdated }) {
  const [status, setStatus]   = useState(order.order_status);
  const [saving, setSaving]   = useState(false);

  const handleUpdate = async () => {
    setSaving(true);
    try {
      await client.put(`/shop-orders/${order.id}`, { order_status: status });
      toast.success('Order status updated');
      onUpdated();
    } catch {
      toast.error('Update failed');
    } finally {
      setSaving(false);
    }
  };

  const statusColor = {
    pending: '#f59e0b', confirmed: '#3b82f6', shipped: '#8b5cf6',
    delivered: '#22c55e', cancelled: '#ef4444',
  };

  return (
    <Modal isOpen onClose={onClose} title={`Order #${order.id}`} wide>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1rem', marginBottom: '1.5rem' }}>
        <div>
          <strong>Customer</strong>
          <p style={{ margin: '4px 0' }}>{order.customer_name}</p>
          <p style={{ margin: '4px 0', color: 'var(--text-light)' }}>{order.customer_phone}</p>
          {order.customer_email && <p style={{ margin: '4px 0', color: 'var(--text-light)' }}>{order.customer_email}</p>}
        </div>
        <div>
          <strong>Product</strong>
          <p style={{ margin: '4px 0' }}>{order.product_name}</p>
          <p style={{ margin: '4px 0', color: 'var(--text-light)' }}>Qty: {order.quantity}</p>
        </div>
        <div>
          <strong>Delivery Address</strong>
          <p style={{ margin: '4px 0', fontSize: '0.9rem' }}>{order.delivery_address}</p>
          <p style={{ margin: '4px 0', color: 'var(--text-light)' }}>Pincode: {order.pincode}</p>
        </div>
        <div>
          <strong>Payment</strong>
          <p style={{ margin: '4px 0' }}>
            <span className={`status-badge status-${order.payment_status === 'paid' ? 'published' : 'draft'}`}>
              {order.payment_status}
            </span>
          </p>
          {order.razorpay_payment_id && (
            <p style={{ margin: '4px 0', fontSize: '0.8rem', color: 'var(--text-light)' }}>
              ID: {order.razorpay_payment_id}
            </p>
          )}
        </div>
        <div>
          <strong>Amount Breakdown</strong>
          <p style={{ margin: '4px 0', fontSize: '0.9rem' }}>
            Product: ₹{parseFloat(order.product_price).toFixed(2)} × {order.quantity}<br />
            Delivery: ₹{parseFloat(order.delivery_charge).toFixed(2)}<br />
            <strong>Total: ₹{parseFloat(order.total_amount).toFixed(2)}</strong>
          </p>
        </div>
        <div>
          <strong>Placed On</strong>
          <p style={{ margin: '4px 0', fontSize: '0.9rem' }}>
            {new Date(order.created_at).toLocaleString('en-IN')}
          </p>
        </div>
      </div>

      <hr style={{ margin: '1rem 0', borderColor: 'var(--border)' }} />
      <div className="form-row" style={{ alignItems: 'flex-end' }}>
        <div className="form-group" style={{ flex: 1 }}>
          <label>Update Order Status</label>
          <select value={status} onChange={(e) => setStatus(e.target.value)}>
            {ORDER_STATUSES.map((s) => (
              <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
            ))}
          </select>
        </div>
        <div className="form-group" style={{ flex: '0 0 auto' }}>
          <button className="btn btn-primary" onClick={handleUpdate} disabled={saving}>
            {saving ? 'Saving...' : 'Update Status'}
          </button>
        </div>
      </div>

      <div className="form-actions">
        <button className="btn btn-outline" onClick={onClose}>Close</button>
      </div>
    </Modal>
  );
}

export default function ShopOrdersPage() {
  const [orders, setOrders]       = useState([]);
  const [loading, setLoading]     = useState(true);
  const [page, setPage]           = useState(1);
  const [total, setTotal]         = useState(0);
  const [filterPayment, setFilterPayment] = useState('');
  const [filterOrder, setFilterOrder]     = useState('');
  const [selectedOrder, setSelectedOrder] = useState(null);
  const perPage = 20;

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const params = { page, per_page: perPage };
      if (filterPayment) params.payment_status = filterPayment;
      if (filterOrder)   params.order_status   = filterOrder;
      const res = await client.get('/shop-orders', { params });
      setOrders(res.data.data || []);
      setTotal(res.data.meta?.total || 0);
    } catch {
      toast.error('Failed to load orders');
    } finally {
      setLoading(false);
    }
  }, [page, filterPayment, filterOrder]);

  useEffect(() => { load(); }, [load]);

  const onUpdated = () => { setSelectedOrder(null); load(); };

  const totalPages = Math.ceil(total / perPage);

  const paymentColor = { pending: '#f59e0b', paid: '#22c55e', failed: '#ef4444', refunded: '#8b5cf6' };
  const orderColor   = { pending: '#f59e0b', confirmed: '#3b82f6', shipped: '#8b5cf6', delivered: '#22c55e', cancelled: '#ef4444' };

  return (
    <div>
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1.5rem' }}>
        <div>
          <h2 style={{ margin: 0 }}>Shop Orders</h2>
          <p style={{ margin: '4px 0 0', color: 'var(--text-light)', fontSize: '0.88rem' }}>
            {total} total orders
          </p>
        </div>
      </div>

      {/* Filters */}
      <div style={{ display: 'flex', gap: '1rem', marginBottom: '1.5rem', flexWrap: 'wrap' }}>
        <select value={filterPayment} onChange={(e) => { setFilterPayment(e.target.value); setPage(1); }}
          style={{ padding: '0.4rem 0.75rem', border: '1px solid var(--border)', borderRadius: 'var(--radius-sm)', minWidth: '160px' }}>
          <option value="">All Payments</option>
          {PAYMENT_STATUSES.map((s) => <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>)}
        </select>
        <select value={filterOrder} onChange={(e) => { setFilterOrder(e.target.value); setPage(1); }}
          style={{ padding: '0.4rem 0.75rem', border: '1px solid var(--border)', borderRadius: 'var(--radius-sm)', minWidth: '160px' }}>
          <option value="">All Order Status</option>
          {ORDER_STATUSES.map((s) => <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>)}
        </select>
      </div>

      {loading ? (
        <p style={{ color: 'var(--text-light)' }}>Loading orders...</p>
      ) : orders.length === 0 ? (
        <div className="card" style={{ textAlign: 'center', padding: '3rem' }}>
          <p style={{ color: 'var(--text-muted)' }}>No orders found.</p>
        </div>
      ) : (
        <div className="card" style={{ overflow: 'auto' }}>
          <table className="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Customer</th>
                <th>Pincode</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {orders.map((o) => (
                <tr key={o.id}>
                  <td><strong>#{o.id}</strong></td>
                  <td style={{ maxWidth: '140px', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>
                    {o.product_name}
                  </td>
                  <td>
                    <div>{o.customer_name}</div>
                    <div style={{ fontSize: '0.8rem', color: 'var(--text-light)' }}>{o.customer_phone}</div>
                  </td>
                  <td>{o.pincode}</td>
                  <td>{o.quantity}</td>
                  <td><strong>₹{parseFloat(o.total_amount).toFixed(2)}</strong></td>
                  <td>
                    <span style={{
                      padding: '2px 8px', borderRadius: '12px', fontSize: '0.75rem', fontWeight: 600,
                      background: paymentColor[o.payment_status] + '22',
                      color: paymentColor[o.payment_status],
                    }}>
                      {o.payment_status}
                    </span>
                  </td>
                  <td>
                    <span style={{
                      padding: '2px 8px', borderRadius: '12px', fontSize: '0.75rem', fontWeight: 600,
                      background: orderColor[o.order_status] + '22',
                      color: orderColor[o.order_status],
                    }}>
                      {o.order_status}
                    </span>
                  </td>
                  <td style={{ fontSize: '0.8rem', color: 'var(--text-light)', whiteSpace: 'nowrap' }}>
                    {new Date(o.created_at).toLocaleDateString('en-IN')}
                  </td>
                  <td>
                    <button className="btn btn-sm btn-outline" onClick={() => setSelectedOrder(o)}>View</button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Pagination */}
      {totalPages > 1 && (
        <div style={{ display: 'flex', gap: '0.5rem', justifyContent: 'center', marginTop: '1.5rem' }}>
          <button className="btn btn-outline btn-sm" disabled={page <= 1} onClick={() => setPage(page - 1)}>← Prev</button>
          <span style={{ padding: '0.4rem 0.75rem', fontSize: '0.9rem' }}>Page {page} / {totalPages}</span>
          <button className="btn btn-outline btn-sm" disabled={page >= totalPages} onClick={() => setPage(page + 1)}>Next →</button>
        </div>
      )}

      {selectedOrder && (
        <OrderDetailModal order={selectedOrder} onClose={() => setSelectedOrder(null)} onUpdated={onUpdated} />
      )}
    </div>
  );
}
