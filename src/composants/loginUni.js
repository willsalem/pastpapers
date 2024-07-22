import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import "./loginUni.css";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';

export default function LoginUni({ setAuthenticated }) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [userType, setUserType] = useState('universite'); // État pour le type d'utilisateur
  const navigate = useNavigate();

  const handleBackClick = () => {
    navigate('/');
  };

  const handleLogin = async (e) => {
    e.preventDefault();

    // Préparer les données d'authentification
    const loginData = {
      emailUni: userType === 'universite' ? email : undefined,
      passwordUni: userType === 'universite' ? password : undefined,
      emailAdmin: userType === 'admin' ? email : undefined,
      passwordAdmin: userType === 'admin' ? password : undefined,
    };

    console.log('Données envoyées:', loginData);

    try {
      const response = await fetch('http://localhost/backend/api/authentification.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(loginData),
      });

      const data = await response.json();
      console.log('Réponse du serveur:', data);

      if (response.ok) {
        setAuthenticated(true);
        switch (data.user_type) {
          case 'universite':
            navigate('/universites');
            break;
          case 'admin':
            navigate('/compte-ad');
            break;
          default:
            alert('Type d\'utilisateur inconnu');
        }
      } else {
        alert(data.message || 'Email ou mot de passe incorrect');
      }
    } catch (error) {
      console.error('Erreur lors de la connexion:', error);
      alert('Une erreur s\'est produite lors de la tentative de connexion');
    }
  };

  return (
    <div className='SamiRath'>
      <FontAwesomeIcon icon={faArrowLeft} className="back-arrow" onClick={handleBackClick} />
      <form className='RathSamiForm' onSubmit={handleLogin}>
        <h2>Connexion</h2>
        <select
          className='RathInput'
          value={userType}
          onChange={(e) => setUserType(e.target.value)}
          required
        >
          <option value="universite">Université</option>
          <option value="admin">Admin</option>
        </select>
        <input
          type="email"
          className='RathInput'
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          className='RathInput'
          placeholder="Mot de passe"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button type="submit" className='RathButton'>Se connecter</button>
        <div>_______ ou _______</div>
        <button type="button" onClick={() => navigate('/inscriptionUni')} className='RathLinkButton'>S'inscrire</button>
      </form>
    </div>
  );
}
