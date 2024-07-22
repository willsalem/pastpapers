import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Modal from 'react-modal';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons';
import './listeUni.css';
import logoPlaceholder from './blacklogo.png'; // Use a default logo placeholder

Modal.setAppElement('#root');

const ListeUni = () => {
  const [universites, setUniversites] = useState([]);
  const [isEditing, setIsEditing] = useState(false);
  const [currentUni, setCurrentUni] = useState({});
  const [selectedLogo, setSelectedLogo] = useState(null);

  useEffect(() => {
    const fetchUniversites = async () => {
      try {
        const response = await axios.get('http://localhost/backend/api/universite.php');
        const fetchedUniversites = response.data.data.map((uni) => ({
          id: uni.idUni,
          nom: uni.nomUni,
          adresse: uni.adresseUni,
          telephone: uni.telephoneUni,
          email: uni.emailUni,
          logo: uni.logo || logoPlaceholder
        }));
        setUniversites(fetchedUniversites);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    };

    fetchUniversites();
  }, []);

  const handleDelete = (id) => {
    if (window.confirm('Action grave ! Vous êtes sur le point de supprimer une université')) {
      setUniversites(universites.filter(uni => uni.id !== id));
    }
  };

  const handleEdit = (uni) => {
    setIsEditing(true);
    setCurrentUni(uni);
    setSelectedLogo(null); // Reset selected logo
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setCurrentUni({ ...currentUni, [name]: value });
  };

  const handleLogoChange = (e) => {
    const file = e.target.files[0];
    setSelectedLogo(URL.createObjectURL(file));
    setCurrentUni({ ...currentUni, logo: file });
  };

  const handleUpdate = (e) => {
    e.preventDefault();
    const updatedUniversites = universites.map(uni => {
      if (uni.id === currentUni.id) {
        return { ...currentUni, logo: selectedLogo || uni.logo };
      }
      return uni;
    });
    setUniversites(updatedUniversites);
    setIsEditing(false);
  };

  return (
    <div className="liste-uni-container">
      <h2>Liste des dernières universités répertoriées</h2>
      <div className="table-responsive">
        <table className="liste-uni-table">
          <thead>
            <tr>
              <th>Logo</th>
              <th>Nom</th>
              <th>Adresse</th>
              <th>Téléphone</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {universites.map((uni) => (
              <tr key={uni.id}>
                <td><img src={uni.logo} alt="Logo" className="uni-logo" /></td>
                <td>{uni.nom}</td>
                <td>{uni.adresse}</td>
                <td>{uni.telephone}</td>
                <td>{uni.email}</td>
                <td className="actions-button">
                  <button className="edit" onClick={() => handleEdit(uni)}>
                    <FontAwesomeIcon icon={faEdit} />
                  </button>
                  <button className="delete" onClick={() => handleDelete(uni.id)}>
                    <FontAwesomeIcon icon={faTrash} />
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <Modal
        isOpen={isEditing}
        onRequestClose={() => setIsEditing(false)}
        contentLabel="Modifier Université"
        className="ReactModal__Content"
        overlayClassName="ReactModal__Overlay"
      >
        <h4>Modifier Université</h4>
        <form onSubmit={handleUpdate} className="modal-form">
          <input
            type="text"
            name="nom"
            placeholder="Nom"
            value={currentUni.nom}
            onChange={handleChange}
            required
          />
          <input
            type="text"
            name="adresse"
            placeholder="Adresse"
            value={currentUni.adresse}
            onChange={handleChange}
            required
          />
          <input
            type="text"
            name="telephone"
            placeholder="Téléphone"
            value={currentUni.telephone}
            onChange={handleChange}
            required
          />
          <input
            type="email"
            name="email"
            placeholder="Email"
            value={currentUni.email}
            onChange={handleChange}
            required
          />
          <input
            type="file"
            name="logo"
            accept="image/*"
            onChange={handleLogoChange}
          />
          {selectedLogo && <img src={selectedLogo} alt="Selected Logo" className="selected-logo-preview" />}
          <button type="submit">Enregistrer</button>
          <button type="button" onClick={() => setIsEditing(false)}>Annuler</button>
        </form>
      </Modal>
    </div>
  );
};

export default ListeUni;
