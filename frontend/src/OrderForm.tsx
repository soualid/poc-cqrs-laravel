import { useState } from 'react';
import { createOrder } from './api';

interface Props {
  onOrderCreated: () => void;
}

export default function OrderForm({ onOrderCreated }: Props) {
  const [customerName, setCustomerName] = useState('');
  const [product, setProduct] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [unitPrice, setUnitPrice] = useState(10);
  const [submitting, setSubmitting] = useState(false);
  const [feedback, setFeedback] = useState<string | null>(null);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setSubmitting(true);
    setFeedback(null);
    try {
      const { id } = await createOrder({
        customer_name: customerName,
        product,
        quantity,
        unit_price: unitPrice,
      });
      setFeedback(`Commande envoyée (${id.slice(0, 8)}…) — en attente de projection`);
      setCustomerName('');
      setProduct('');
      setQuantity(1);
      setUnitPrice(10);
      onOrderCreated();
    } catch (err: unknown) {
      setFeedback(`Erreur : ${err instanceof Error ? err.message : 'Inconnue'}`);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <form onSubmit={handleSubmit} className="order-form">
      <h2>Nouvelle commande <span className="badge write">WRITE</span></h2>
      <div className="form-grid">
        <label>
          Client
          <input value={customerName} onChange={e => setCustomerName(e.target.value)} required />
        </label>
        <label>
          Produit
          <input value={product} onChange={e => setProduct(e.target.value)} required />
        </label>
        <label>
          Quantité
          <input type="number" min={1} value={quantity} onChange={e => setQuantity(+e.target.value)} required />
        </label>
        <label>
          Prix unitaire (€)
          <input type="number" min={0.01} step={0.01} value={unitPrice} onChange={e => setUnitPrice(+e.target.value)} required />
        </label>
      </div>
      <button type="submit" disabled={submitting}>
        {submitting ? 'Envoi…' : 'Envoyer la commande'}
      </button>
      {feedback && <p className="feedback">{feedback}</p>}
    </form>
  );
}
