import React from 'react';
import { Link, useNavigate } from 'react-router-dom';
import './Sidebar.css';
import logo from 'C:/wamp64/www/pastpapers/src/composants/blacklogo.png';

function Sidebar() {
  const navigate = useNavigate();

  const handleLogoutClick = (e) => {
    e.preventDefault();
    if (window.confirm('Vous êtes sur le point de vous déconnecter !')) {
      // Ajouter la logique de déconnexion ici
      console.log('Déconnexion réussie');
      navigate('/loginUni'); // Rediriger vers la page de connexion après déconnexion
    } else {
      console.log('Déconnexion annulée');
    }
  };

  return (
    <div className="sidebar">
        <img src={logo} alt="Logo" className="logo" />
      <div className="sidebar-header">
      </div>
      <ul className="sidebar-menu">
        <li><Link to="/">Accueil</Link></li>
        <li className='bx'> <Link to="/">Paramètres</Link></li>
        <li>    
        <a href="/login" className="logout" onClick={handleLogoutClick}>
				<i class='bx' ></i>
				<span class="text" onclick="()=>{window.location.href='./loginUni'}">Déconnexion</span>
			</a>
        </li>
      </ul>
    </div>
  );
}

export default Sidebar;
