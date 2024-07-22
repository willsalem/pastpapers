import React, { useEffect, useState } from 'react';
import { FaEdit, FaCheckCircle, FaTimesCircle } from 'react-icons/fa';
import './ListeEn.css';

function ListeEn() {
    const [enseignants, setEnseignants] = useState([]);
    const [editingEnseignant, setEditingEnseignant] = useState(null);

    useEffect(() => {
        const fetchEnseignants = async () => {
            try {
                const response = await fetch('http://localhost/backend/api/enseignant.php');
                if (!response.ok) {
                    throw new Error('Erreur lors de la récupération des enseignants');
                }
                const result = await response.json();
                console.log('Réponse de l\'API:', result); // Journal de la réponse de l'API
                if (result.data) {
                    setEnseignants(result.data);
                } else {
                    setEnseignants([]);
                }
            } catch (error) {
                console.error('Erreur:', error);
                setEnseignants([]);
            }
        };

        fetchEnseignants();
    }, []);

    const handleEditClick = (enseignant) => {
        setEditingEnseignant(enseignant);
    };

    const handleConfirm = (id) => {
        // Mise à jour de l'état de l'enseignant (statut confirmé)
        setEnseignants(prevEnseignants =>
            prevEnseignants.map(enseignant =>
                enseignant.idEnseignant === id ? { ...enseignant, statut: 'confirmé' } : enseignant
            )
        );
        setEditingEnseignant(null);
    };

    const handleDeny = (id) => {
        // Mise à jour de l'état de l'enseignant (statut infirmé)
        setEnseignants(prevEnseignants =>
            prevEnseignants.map(enseignant =>
                enseignant.idEnseignant === id ? { ...enseignant, statut: 'infirmé' } : enseignant
            )
        );
        setEditingEnseignant(null);
    };

    const closeModal = () => {
        setEditingEnseignant(null);
    };

    return (
        <div className='Wawi'>
            <h2>Liste des Enseignants</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Matière</th>
                        <th>Sexe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {enseignants.length > 0 ? (
                        enseignants.map((enseignant, index) => (
                            <tr key={index}>
                                <td>{enseignant.nomEnseignant}</td>
                                <td>{enseignant.prenomEnseignant}</td>
                                <td>{enseignant.telephoneEnseignant}</td>
                                <td>{enseignant.emailEnseignant}</td>
                                <td>{enseignant.matiere}</td>
                                <td>{enseignant.sexeEnseignant}</td>
                                <td>
                                    {enseignant.statut === 'confirmé' ? (
                                        <FaCheckCircle color="green" />
                                    ) : enseignant.statut === 'infirmé' ? (
                                        <FaTimesCircle color="red" />
                                    ) : (
                                        <FaEdit onClick={() => handleEditClick(enseignant)} />
                                    )}
                                </td>
                            </tr>
                        ))
                    ) : (
                        <tr>
                            <td colSpan="7">Aucun enseignant trouvé.</td>
                        </tr>
                    )}
                </tbody>
            </table>

            {editingEnseignant && (
                <>
                    <div className="modal-overlay" onClick={closeModal}></div>
                    <div className="modal">
                        <h3>Modifier le statut de l'enseignant</h3>
                        <p>Nom: {editingEnseignant.nomEnseignant}</p>
                        <p>Prénom: {editingEnseignant.prenomEnseignant}</p>
                        <button onClick={() => handleConfirm(editingEnseignant.idEnseignant)}>Confirmer</button>
                        <button onClick={() => handleDeny(editingEnseignant.idEnseignant)}>Infirmer</button>
                        <button onClick={closeModal}>Fermer</button>
                    </div>
                </>
            )}
        </div>
    );
}

export default ListeEn;
