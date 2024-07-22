import React, { useState } from 'react';
import 'C:/wamp64/www/pastpapers/src/css/signup.css';
import { useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';

function Inscription() {
    const [nom, setNom] = useState('');
    const [prenom, setPrenom] = useState('');
    const [email, setEmail] = useState('');
    const [matiere, setMatiere] = useState('');
    const [password, setPassword] = useState('');
    const [sexe, setSexe] = useState('');
    const [telephone, setTelephone] = useState('');

    const navigate = useNavigate();

    const handleBackClick = () => {
        navigate('/');
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('nomEnseignant', nom);
        formData.append('prenomEnseignant', prenom);
        formData.append('emailEnseignant', email);
        formData.append('matiere', matiere);
        formData.append('passwordEnseignant', password);
        formData.append('sexeEnseignant', sexe);
        formData.append('telephoneEnseignant', telephone);
        formData.append('action', 'SAVE_ENSEIGNANT');

        try {
            const response = await fetch('http://localhost/backend/api/enseignant.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Erreur lors de l\'inscription de l\'enseignant');
            }

            const result = await response.json();
            alert(result.message);
            
            // Redirection vers la page de connexion après l'inscription réussie
            navigate('/login');
        } catch (error) {
            console.error('Erreur lors de l\'inscription de l\'enseignant:', error);
            alert('Erreur lors de l\'inscription de l\'enseignant');
        }
    };

    return (
        <div className='Wawou'>
            <FontAwesomeIcon icon={faArrowLeft} className="back-arrow" onClick={handleBackClick} />
            <input type="radio" className="tabs__button" name="Inscription" id="InscriptionEn" checked />

            <div className="tabs__content">
                <h1>Bienvenue</h1>
                <form className="form" id="form1" onSubmit={handleSubmit}>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="text"
                            placeholder="&nbsp;"
                            name="nom"
                            id="nom"
                            autoComplete="off"
                            required
                            value={nom}
                            onChange={(e) => setNom(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="nom">*Nom</label>
                    </div>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="text"
                            name="prenom"
                            placeholder="&nbsp;"
                            id="prenom"
                            required
                            value={prenom}
                            onChange={(e) => setPrenom(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="prenom">*Prénom</label>
                    </div>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="email"
                            name="email"
                            placeholder="&nbsp;"
                            id="email"
                            required
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="email">*Email</label>
                    </div>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="text"
                            name="matiere"
                            placeholder="&nbsp;"
                            id="matiere"
                            required
                            value={matiere}
                            onChange={(e) => setMatiere(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="matiere">*Matière</label>
                    </div>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="password"
                            name="password"
                            placeholder="&nbsp;"
                            id="password"
                            required
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="password">*Mot de passe</label>
                    </div>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="text"
                            name="sexe"
                            placeholder="&nbsp;"
                            id="sexe"
                            required
                            value={sexe}
                            onChange={(e) => setSexe(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="sexe">*Sexe</label>
                    </div>
                    <div className="input-group">
                        <input
                            className="input-group__input"
                            type="tel"
                            name="telephone"
                            placeholder="&nbsp;"
                            id="telephone"
                            required
                            value={telephone}
                            onChange={(e) => setTelephone(e.target.value)}
                        />
                        <label className="input-group__label" htmlFor="telephone">*Téléphone</label>
                    </div>
                    <button type="submit">Envoyer</button>
                </form>
            </div>
        </div>
    );
}

export default Inscription;
