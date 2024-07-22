// Menu.js
import React, { useState, useEffect } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { Link as ScrollLink } from 'react-scroll';
import { FaSearch, FaUserCircle } from 'react-icons/fa';
import logo from './bluelogonoirr.png';
import "./Menu.css";
import { useAuth } from './AuthContext';

export default function Menu({ onSearch }) {
  const [searchVisible, setSearchVisible] = useState(false);
  const [matiere, setMatiere] = useState('');
  const [selectedAnnee, setSelectedAnnee] = useState('');
  const [isScrolled, setIsScrolled] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout } = useAuth();

  const handleSearchIconClick = () => {
    setSearchVisible(!searchVisible);
  };

  const handleSearch = () => {
    onSearch({ matiere, annee: selectedAnnee });
    setMatiere('');
    setSelectedAnnee('');
    setSearchVisible(false);
  };

  const handleLoginClick = () => {
    if (user) {
      logout();
      navigate('/');
    } else {
      navigate('/login');
    }
  };

  useEffect(() => {
    const handleScroll = () => {
      const scrollTop = window.scrollY;
      setIsScrolled(scrollTop > 600); // Changer 600 si nécessaire pour votre besoin
    };

    window.addEventListener('scroll', handleScroll);

    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  }, []);

  const renderUserIcon = () => {
    if (user) {
      return (
        <div className="user-container" onClick={handleLoginClick}>
          <FaUserCircle className="default-user-icon" />
          <span className="user-text">Déconnexion</span>
        </div>
      );
    } else {
      return (
        <div className="user-container" onClick={handleLoginClick}>
          <FaUserCircle className="default-user-icon" />
          <span className="user-text">Connexion</span>
        </div>
      );
    }
  };

  const shouldShowMenu = ['/', '/catalogue', '/login', '/inscription', '/InscriptionAp'].includes(location.pathname);

  return (
    shouldShowMenu && (
      <nav className={`menu ${isScrolled ? 'menu--hidden' : ''}`}>
        <img src={logo} alt="Logo" className="logo" />
        <div className="menu-items">
          <Link to="/" className={`menu-item ${location.pathname === '/' ? 'active' : ''}`}>Accueil</Link>
          <ScrollLink to="SectionInfo-section" smooth={true} duration={500} className="menu-item">
            À propos
          </ScrollLink>
          <ScrollLink to="epreuves-section" smooth={true} duration={500} className="menu-item">
          Épreuves
          </ScrollLink>
          <ScrollLink to="solutions-section" smooth={true} duration={500} className="menu-item">
            Solutions
          </ScrollLink>
          <Link to="/catalogue" className={`menu-item ${location.pathname === '/catalogue' ? 'active' : ''}`}>Catalogue</Link>
          <Link to="/Universites" className={`menu-item ${location.pathname === '/Universites' ? 'active' : ''}`}>Universités</Link>
        </div>
        <FaSearch className="search-icon" onClick={handleSearchIconClick} />
        {searchVisible && (
          <div className="search-bar">
            <div className="search-input">
              <span>Matière</span>
              <input
                type="text"
                placeholder="Matière"
                value={matiere}
                onChange={(e) => setMatiere(e.target.value)}
              />
            </div>
            <div className="search-input">
              <div className='xill'>
                <span>Année</span>
              </div>
              <input
                type="text"
                placeholder="Année"
                value={selectedAnnee}
                onChange={(e) => setSelectedAnnee(e.target.value)}
              />
            </div>
            <button onClick={handleSearch}>Rechercher</button>
          </div>
        )}
        {renderUserIcon()}
      </nav>
    )
  );
}
