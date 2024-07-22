// Header.js
import React from 'react';
import { useAuth } from '../AuthContext';
import './Header.css';

function Header() {
  const { user } = useAuth();

  return (
    <div className="header">
      <h3>Bienvenue sur votre Tableau de Bord{user ? ` ${user.user.prenom} ${user.user.nom}` : ''}</h3>
    </div>
  );
}

export default Header;
