import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';
import { useAuth } from '../AuthContext';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import './login.css';

function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [userType, setUserType] = useState('enseignant'); // Change default to 'enseignant' for testing
  const [showModal, setShowModal] = useState(false);
  const navigate = useNavigate();
  const { login } = useAuth();

  const handleBackClick = () => {
    navigate('/');
  };

  const handleLogin = async (e) => {
    e.preventDefault();

    console.log("userType:", userType); // Log the userType

    let loginData;
    switch (userType) {
      case 'enseignant':
        loginData = { emailEnseignant: email, passwordEnseignant: password };
        break;
      case 'apprenant':
        loginData = { emailAp: email, passwordAp: password };
        break;
      default:
        toast.error('Type d\'utilisateur inconnu');
        return;
    }

    try {
      const response = await fetch('http://localhost/backend/api/authentification.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(loginData),
      });

      const data = await response.json();

      if (response.ok) {
        login({ ...data, userType: userType });
        if (userType === 'enseignant') {
          navigate('/compte-en');
        } else if (userType === 'apprenant') {
          navigate('/');
        }
      } else {
        toast.error(data.message || 'Email ou mot de passe incorrect');
      }
    } catch (error) {
      console.error('Erreur lors de la connexion:', error);
      toast.error('Une erreur s\'est produite lors de la tentative de connexion');
    }
  };

  const handleSignupClick = () => {
    setShowModal(true);
  };

  const handleCloseModal = () => {
    setShowModal(false);
  };

  const handleSignupChoice = (type) => {
    setShowModal(false);
    if (type === 'enseignant') {
      navigate('/inscription');
    } else if (type === 'étudiant') {
      navigate('/InscriptionAp');
    }
  };

  return (
    <div className='JolivetContainer'>
      <ToastContainer />
      <FontAwesomeIcon icon={faArrowLeft} className="back-arrow" onClick={handleBackClick} />
      <form className='JolivetForm' onSubmit={handleLogin}>
        <h2 className='JolivetTitle'>Connexion</h2>
        <select
          className='JolivetInput'
          value={userType}
          onChange={(e) => setUserType(e.target.value)}
        >
          <option value="enseignant">Enseignant</option>
          <option value="apprenant">Apprenant</option>
        </select>
        <input
          type="email"
          className='JolivetInput'
          placeholder="Email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          className='JolivetInput'
          placeholder="Mot de passe"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button type="submit" className='JolivetButton'>Se connecter</button>
        <div>_______ ou _______</div>
        <button type="button" className='RomsiathLinkButton' onClick={handleSignupClick}>S'inscrire</button>
      </form>

      {showModal && (
        <div className="modal-overlay">
          <div className="modal-content">
            <h3>Choisissez votre type d'inscription</h3>
            <button className="modal-button" onClick={() => handleSignupChoice('enseignant')}>Enseignant</button>
            <button className="modal-button" onClick={() => handleSignupChoice('étudiant')}>Étudiant</button>
            <button className="modal-close-button" onClick={handleCloseModal}>Fermer</button>
          </div>
        </div>
      )}
    </div>
  );
}

export default Login;
