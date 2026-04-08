import { useEffect, useState } from 'react';
import { fetchOrders, type Order } from './api';

interface Props {
  refreshKey: number;
}

export default function OrderList({ refreshKey }: Props) {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let active = true;

    async function load() {
      try {
        const data = await fetchOrders();
        if (active) setOrders(data);
      } catch {
        // silently retry on next tick
      } finally {
        if (active) setLoading(false);
      }
    }

    load();
    const interval = setInterval(load, 2000);
    return () => { active = false; clearInterval(interval); };
  }, [refreshKey]);

  return (
    <div className="order-list">
      <h2>
        Commandes <span className="badge read">READ</span>
        <span className="auto-refresh">auto-refresh 2s</span>
      </h2>
      {loading ? (
        <p className="empty">Chargement…</p>
      ) : orders.length === 0 ? (
        <p className="empty">Aucune commande projetée pour le moment.</p>
      ) : (
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Client</th>
              <th>Produit</th>
              <th>Qté</th>
              <th>Prix unit.</th>
              <th>Total</th>
              <th>Statut</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            {orders.map(order => (
              <tr key={order.id}>
                <td className="mono">{order.id.slice(0, 8)}</td>
                <td>{order.customer_name}</td>
                <td>{order.product}</td>
                <td>{order.quantity}</td>
                <td>{Number(order.unit_price).toFixed(2)} €</td>
                <td className="total">{Number(order.total).toFixed(2)} €</td>
                <td><span className={`status ${order.status}`}>{order.status}</span></td>
                <td>{new Date(order.created_at).toLocaleTimeString('fr-FR')}</td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
}
