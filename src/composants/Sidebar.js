import React from 'react';
import { Link } from 'react-router-dom';
import './Sidebar.css';
import logo from 'C:/wamp64/www/pastpapers/src/composants/blacklogo.png';

function Sidebar() {
  return (
    <div className="sidebar">
        <img src={logo} alt="Logo" className="logo" />
      <div className="sidebar-header">
      </div>
      <ul className="sidebar-menu">
        <li><Link to="/">Aller à l'accueil</Link></li>
        <li><Link to="/">Favoris</Link></li>
        <li><Link to="/">Télécharger</Link></li>
        <li className='bx'> <Link to="/">Paramètres</Link></li>
        <li>    
            <a href="./login" class="logout">
				<i class='bx' ></i>
				<span class="text" onclick="()=>{window.location.href='./login'}">Déconnexion</span>
			</a>
        </li>
      </ul>
    </div>
  );
}

export default Sidebar;
