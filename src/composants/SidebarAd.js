import React from 'react';
import { useNavigate } from 'react-router-dom';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import './Sidebar.css';
import logo from 'C:/wamp64/www/pastpapers/src/composants/blacklogo.png';

function Sidebar({ activeSection, setActiveSection }) {
  const navigate = useNavigate();

  const handleLogoutClick = (e) => {
    e.preventDefault();
    if (window.confirm('Vous êtes sur le point de vous déconnecter !')) {
      toast.success('Déconnexion réussie');
      console.log('Déconnexion réussie');
      setTimeout(() => {
        navigate('/loginUni');
      }, 2000); // Attendre 2 secondes avant de rediriger pour permettre l'affichage de la notification
    } else {
      toast.info('Déconnexion annulée');
      console.log('Déconnexion annulée');
    }
  };

  return (
    <div className="sidebar">
      <img src={logo} alt="Logo" className="logo" />
      <div className="sidebar-header"></div>
      <ul className="sidebar-menu">
        <li className={activeSection === 'dashboard' ? 'active' : ''}>
          <button onClick={() => setActiveSection('dashboard')}>Tableau de Bord</button>
        </li>
        <li className={activeSection === 'statistique' ? 'active' : ''}>
          <button onClick={() => setActiveSection('statistique')}>Statistique</button>
        </li>
        <li className={activeSection === 'parametres' ? 'active' : ''}>
          <button onClick={() => setActiveSection('parametres')}>Paramètres</button>
        </li>
        <li>
          <button className="logout" onClick={handleLogoutClick}>
            <span className="text">Déconnexion</span>
          </button>
        </li>
      </ul>
      <ToastContainer />
    </div>
  );
}

export default Sidebar;
