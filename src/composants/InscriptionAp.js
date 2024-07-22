import React, { useState } from 'react';
import './InscriptionAp.css';
import { useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';

function InscriptionEtudiant() {
    const [nom, setNom] = useState('');
    const [prenom, setPrenom] = useState('');
    const [email, setEmail] = useState('');
    const [sexe, setSexe] = useState('');
    const [password, setPassword] = useState('');
    const [telephone, setTelephone] = useState('');
    const navigate = useNavigate();

    const handleBackClick = () => {
        navigate('/');
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        formData.append('nomAp', nom);
        formData.append('prenomAp', prenom);
        formData.append('emailAp', email);
        formData.append('sexeAp', sexe);
        formData.append('passwordAp', password);
        formData.append('telephoneAp', telephone);
        formData.append('action', 'SAVE');

        try {
            const response = await fetch('http://localhost/backend/api/apprenant.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const errorText = await response.text(); // Obtenir la réponse brute pour le débogage
                console.error('Réponse du serveur:', errorText);
                throw new Error('Erreur lors de l\'inscription de l\'étudiant');
            }

            const result = await response.json();
            alert(result.message);

            if (result.code === "100") {
                navigate('/login'); // Redirection vers la page de connexion après succès
            }
        } catch (error) {
            console.error('Erreur lors de l\'inscription de l\'étudiant:', error);
            alert('Erreur lors de l\'inscription de l\'étudiant: ' + error.message);
        }
    };

    return (
        <div className='Wazi-container'>
            <FontAwesomeIcon icon={faArrowLeft} className="back-arrow" onClick={handleBackClick} />
            <h1>Inscription Étudiant</h1>
            <form className="Salima-form" onSubmit={handleSubmit}>
                <div className="Salima-inputGroup">
                    <input
                        type="text"
                        placeholder="Nom"
                        required
                        value={nom}
                        onChange={(e) => setNom(e.target.value)}
                    />
                </div>
                <div className="Salima-inputGroup">
                    <input
                        type="text"
                        placeholder="Prénom"
                        required
                        value={prenom}
                        onChange={(e) => setPrenom(e.target.value)}
                    />
                </div>
                <div className="Salima-inputGroup">
                    <input
                        type="email"
                        placeholder="Email"
                        required
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                    />
                </div>
                <div className="Salima-inputGroup">
                    <input
                        type="text"
                        placeholder="Sexe"
                        required
                        value={sexe}
                        onChange={(e) => setSexe(e.target.value)}
                    />
                </div>
                <div className="Salima-inputGroup">
                    <input
                        type="password"
                        placeholder="Mot de passe"
                        required
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                    />
                </div>
                <div className="Salima-inputGroup">
                    <input
                        type="tel"
                        placeholder="Téléphone"
                        required
                        value={telephone}
                        onChange={(e) => setTelephone(e.target.value)}
                    />
                </div>
                <button className="Sultan-button" type="submit">Envoyer</button>
            </form>
        </div>
    );
}

export default InscriptionEtudiant;
