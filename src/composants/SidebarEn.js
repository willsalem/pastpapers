import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import './Sidebar.css';
import logo from './blacklogo.png';

function Sidebar() {
  const navigate = useNavigate();

  const handleLogoutClick = (e) => {
    e.preventDefault();
    if (window.confirm('Vous êtes sur le point de vous déconnecter !')) {
      // Ajouter la logique de déconnexion ici
      console.log('Déconnexion réussie');
      navigate('/login'); // Rediriger vers la page de connexion après déconnexion
    } else {
      console.log('Déconnexion annulée');
    }
  };

  return (
    <div className="sidebar">
      <img src={logo} alt="Logo" className="logo" />
      <div className="sidebar-header"></div>
      <ul className="sidebar-menu">
        <li><Link to="/compte-en">Tableau de bord</Link></li>
        <li><Link to="/">Revenir à l'accueil</Link></li>
        <li><Link to="/">Téléchargements</Link></li>
        <li className="bx"><Link to="/">Corrigées</Link></li>
        <li>
          <a href="/login" className="logout" onClick={handleLogoutClick}>
            <i className="bx"></i>
            <span className="text">Déconnexion</span>
          </a>
        </li>
      </ul>
    </div>
  );
}

export default Sidebar;
