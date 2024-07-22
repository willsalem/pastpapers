import React from 'react';
import './Dashboard.css';
import Analytics from './Analytics';

function Dashboard() {
  return (
    <div className="dashboard">
      <div className="stats-cards">
        <div className="card">Ventes Reçu</div>
        <div className="card">Total des ventes</div>
        <div className="card">Revenue</div>
        <div className="card">Total des Revenues</div>
      </div>
      <Analytics />
      {/* Ajouter d'autres sections ici */}
    </div>
  );
}

export default Dashboard;
