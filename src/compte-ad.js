import React, { useState, useEffect } from 'react';
import Sidebar from './composants/SidebarAd';
import Header from './composants/Header';
import Dashboard from './composants/Dashboard';
import ListeUni from './composants/listeUni';
import Analytics from './composants/Analytics';
import Preloader from './Preloader';
import './Moncompte.css';

const stats = {
  epreuves: 18,
  enseignants: 1,
  telechargements: 200,
  universites: 10,
};

function CompteAd() {
  const [loading, setLoading] = useState(true);
  const [activeSection, setActiveSection] = useState('dashboard');

  useEffect(() => {
    const timer = setTimeout(() => {
      setLoading(false);
    }, 500);

    return () => clearTimeout(timer);
  }, []);

  if (loading) {
    return <Preloader />;
  }

  const renderSection = () => {
    switch (activeSection) {
      case 'dashboard':
        return (
          <>
            <Analytics stats={stats} detailed={false} />
            <ListeUni />
          </>
        );
      case 'statistique':
        return <Analytics stats={stats} detailed={true} />;
      case 'liste-uni':
        return <ListeUni />;
      default:
        return <Dashboard />;
    }
  };

  return (
    <div className="Moncompte">
      <Sidebar activeSection={activeSection} setActiveSection={setActiveSection} />
      <div className="main-content">
        <Header />
        <div className="dashboard">
          {renderSection()}
        </div>
      </div>
    </div>
  );
}

export default CompteAd;
