import React, { useState, useEffect, useCallback } from 'react';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import './Epreuvess.css'; // Réutilisation du même fichier CSS pour garder la même apparence
import imagesss from "./epreuvelogo.png";

const EpreuvesGrid = ({ searchCriteria, limit }) => {
  const [epreuves, setEpreuves] = useState([]);
  const [filteredEpreuves, setFilteredEpreuves] = useState([]);
  const [downloading, setDownloading] = useState({});

  const fetchEpreuves = useCallback(async () => {
    try {
      const response = await fetch(`http://localhost/backend/api/epreuve.php?limit=${limit}`);
      if (!response.ok) {
        throw new Error('Erreur lors de la récupération des épreuves');
      }
      const data = await response.json();
      setEpreuves(data.data.epreuves);
      setFilteredEpreuves(data.data.epreuves);
    } catch (error) {
      console.error('Erreur lors de la récupération des épreuves:', error);
      alert('Erreur lors de la récupération des épreuves');
    }
  }, [limit]);

  useEffect(() => {
    fetchEpreuves();
  }, [fetchEpreuves]);

  useEffect(() => {
    const { matiere, annee } = searchCriteria;
    const filtered = epreuves.filter(epreuve => {
      const matchMatiere = matiere ? epreuve.matiere.toLowerCase().includes(matiere.toLowerCase()) : true;
      const matchAnnee = annee ? epreuve.annee === annee : true;
      return matchMatiere && matchAnnee;
    });
    setFilteredEpreuves(filtered);
  }, [searchCriteria, epreuves]);

  const handleDownload = async (epreuve) => {
    if (!epreuve || !epreuve.idEpreuve) {
      console.error("Erreur : objet épreuve invalide", epreuve);
      alert("Erreur : impossible de télécharger l'épreuve");
      return;
    }

    setDownloading(prev => ({ ...prev, [epreuve.idEpreuve]: true }));
    toast.info('Le téléchargement a commencé.');

    try {
      const response = await fetch('http://localhost/backend/api/telechargement.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
          action: 'TELECHARGEMENT', 
          idEpreuve: epreuve.idEpreuve 
        })
      });

      if (!response.ok) {
        console.warn('Erreur lors de l\'enregistrement du téléchargement dans la base de données');
      } else {
        const result = await response.json();
        if (result.code !== 100) {
          console.warn('Enregistrement du téléchargement non réussi:', result.message);
        }
      }
    } catch (error) {
      console.error('Erreur lors de l\'enregistrement du téléchargement:', error);
    } finally {
      if (epreuve.file_pdf) {
        const cleanFilename = epreuve.file_pdf.replace('assets/fichiers/', '');
        const downloadUrl = `http://localhost/backend/api/download.php?file=${encodeURIComponent(cleanFilename)}`;
        window.location.href = downloadUrl;
      } else {
        alert("Le fichier PDF n'est pas disponible pour cette épreuve.");
      }
      setDownloading(prev => ({ ...prev, [epreuve.idEpreuve]: false }));
      toast.success('Le téléchargement est terminé.');
    }
  };

  return (
    <div className="epreuves-container">
      <ToastContainer />
      <h2>Catalogue des épreuves disponibles</h2>
      <div className="epreuve-grid">
        {filteredEpreuves.slice(0, 10).map((epreuve) => (
          <div className="epreuve-card" key={epreuve.idEpreuve}>
            <img src={epreuve.image || imagesss} alt={epreuve.matiere} className="epreuve-image" />
            <div className="epreuve-details">
              <h3>{epreuve.matiere}</h3>
              <p>{epreuve.annee}</p>
              <p>{epreuve.typeEp}</p>
              <div className="epreuve-actions">
                <button 
                  onClick={() => handleDownload(epreuve)}
                  disabled={downloading[epreuve.idEpreuve]}
                >
                  {downloading[epreuve.idEpreuve] ? 'Téléchargement...' : 'Télécharger PDF'}
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default EpreuvesGrid;
