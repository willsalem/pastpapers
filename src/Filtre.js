import React, { useState } from 'react';
import './Filtre.css'; // Ajout du fichier CSS pour les styles

const Filter = ({ onChange }) => {
  const [matiere, setMatiere] = useState('');
  const [annee, setAnnee] = useState('');

  const handleMatiereChange = (e) => {
    setMatiere(e.target.value);
    onChange({ matiere: e.target.value, annee });
  };

  const handleAnneeChange = (e) => {
    setAnnee(e.target.value);
    onChange({ matiere, annee: e.target.value });
  };

  return (
    <div className="filter-container">
      <h3 className="filter-title">Filtrer les épreuves</h3>
      <div className="filter-field">
        <label className="filter-label">Matière</label>
        <input 
          type="text" 
          value={matiere} 
          onChange={handleMatiereChange} 
          className="filter-input"
        />
      </div>
      <div className="filter-field">
        <label className="filter-label">Année</label>
        <input 
          type="number" 
          value={annee} 
          onChange={handleAnneeChange} 
          className="filter-input"
        />
      </div>
    </div>
  );
};

export default Filter;
