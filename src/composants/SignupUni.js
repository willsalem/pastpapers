import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import "C:/wamp64/www/pastpapers/src/css/SignupUni.css";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';

export default function SignupUni() {
  const [nomUni, setNomUni] = useState('');
  const [adresseUni, setAdresseUni] = useState('');
  const [emailUni, setEmailUni] = useState('');
  const [telephoneUni, setTelephoneUni] = useState('');
  const [passwordUni, setPasswordUni] = useState('');
  const [confirmPasswordUni, setConfirmPasswordUni] = useState('');
  const [logo, setLogo] = useState(null);
  const navigate = useNavigate();

  const handleBackClick = () => {
    navigate('/');
  };

  const handleSignup = async (e) => {
    e.preventDefault();

    if (passwordUni !== confirmPasswordUni) {
      alert('Les mots de passe ne correspondent pas.');
      return;
    }

    const formData = new FormData();
    formData.append('nomUni', nomUni);
    formData.append('adresseUni', adresseUni);
    formData.append('emailUni', emailUni);
    formData.append('telephoneUni', telephoneUni);
    formData.append('passwordUni', passwordUni);
    formData.append('action', 'CREATE_UNIVERSITY');
    if (logo) {
      formData.append('logo_img', logo);
    }

    try {
      const response = await axios.post('http://localhost/backend/api/universite.php', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      console.log('API Response:', response.data);
      if (response.data.success) {
        alert('Inscription réussie');
        navigate('/loginUni');
      } else {
        alert(  response.data.message);
        navigate('/loginUni');
      }
    } catch (error) {
      console.error('Error during signup:', error);
      alert('Erreur lors de l\'inscription');
    }
  };

  return (
    <div className='WillSalem'>
      <FontAwesomeIcon icon={faArrowLeft} className="back-arrow" onClick={handleBackClick} />
      <form className='SalemWillForm' onSubmit={handleSignup}>
        <input
          type="text"
          className='WillInput'
          placeholder="Nom"
          value={nomUni}
          onChange={(e) => setNomUni(e.target.value)}
          required
        />
        <input
          type="text"
          className='WillInput'
          placeholder="Adresse"
          value={adresseUni}
          onChange={(e) => setAdresseUni(e.target.value)}
          required
        />
        <input
          type="email"
          className='WillInput'
          placeholder="Email"
          value={emailUni}
          onChange={(e) => setEmailUni(e.target.value)}
          required
        />
        <input
          type="text"
          className='WillInput'
          placeholder="Téléphone"
          value={telephoneUni}
          onChange={(e) => setTelephoneUni(e.target.value)}
          required
        />
        <input
          type="file"
          className='WillInputFile'
          accept="image/*"
          name="logo_img"
          onChange={(e) => setLogo(e.target.files[0])}
          required
        />
        <input
          type="password"
          className='WillInput'
          placeholder="Mot de passe"
          value={passwordUni}
          onChange={(e) => setPasswordUni(e.target.value)}
          required
        />
        <input
          type="password"
          className='WillInput'
          placeholder="Confirmer Mot de passe"
          value={confirmPasswordUni}
          onChange={(e) => setConfirmPasswordUni(e.target.value)}
          required
        />
        <button type="submit" className='WillButton'>S'inscrire</button>
      </form>
    </div>
  );
}
