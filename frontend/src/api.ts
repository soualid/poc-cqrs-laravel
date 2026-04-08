const API_BASE = '/api';

export interface Order {
  id: string;
  customer_name: string;
  product: string;
  quantity: number;
  unit_price: number;
  total: number;
  status: string;
  created_at: string;
}

export interface CreateOrderPayload {
  customer_name: string;
  product: string;
  quantity: number;
  unit_price: number;
}

export async function fetchOrders(): Promise<Order[]> {
  const res = await fetch(`${API_BASE}/orders`);
  if (!res.ok) throw new Error('Failed to fetch orders');
  return res.json();
}

export async function createOrder(payload: CreateOrderPayload): Promise<{ id: string }> {
  const res = await fetch(`${API_BASE}/orders`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
    body: JSON.stringify(payload),
  });
  if (!res.ok) {
    const err = await res.json().catch(() => ({}));
    throw new Error(err.message || 'Failed to create order');
  }
  return res.json();
}
