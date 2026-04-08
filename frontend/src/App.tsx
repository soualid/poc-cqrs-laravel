import { useState } from 'react';
import OrderForm from './OrderForm';
import OrderList from './OrderList';
import './App.css';

export default function App() {
  const [refreshKey, setRefreshKey] = useState(0);

  return (
    <div className="app">
      <header>
        <h1>POC CQRS</h1>
        <p className="subtitle">Command Query Responsibility Segregation — React + Laravel + Redis</p>
        <div className="arch-diagram">
          <span className="node write">POST /api/orders</span>
          <span className="arrow">→</span>
          <span className="node queue">Redis (commands)</span>
          <span className="arrow">→</span>
          <span className="node db">Write DB</span>
          <span className="arrow">→</span>
          <span className="node queue">Redis (projections)</span>
          <span className="arrow">→</span>
          <span className="node db">Read DB</span>
          <span className="arrow">→</span>
          <span className="node read">GET /api/orders</span>
        </div>
      </header>

      <main>
        <OrderForm onOrderCreated={() => setRefreshKey(k => k + 1)} />
        <OrderList refreshKey={refreshKey} />
      </main>
    </div>
  );
}
