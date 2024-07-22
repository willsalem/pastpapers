import React, { useState, useEffect, useCallback } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useAuth } from './AuthContext';
import Slider from 'react-slick';
import "slick-carousel/slick/slick.css"; 
import "slick-carousel/slick/slick-theme.css";
import imagesss from "./epreuvelogo.png";
import './Epreuves.css';

const Epreuves = ({ searchCriteria, limit }) => {
  const [epreuves, setEpreuves] = useState([]);
  const [filteredEpreuves, setFilteredEpreuves] = useState([]);
  const [selectedEpreuve, setSelectedEpreuve] = useState(null);
  const [downloading, setDownloading] = useState({});
  const { user } = useAuth();
  const navigate = useNavigate();

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
    if (!user) {
      navigate('/login');
      return;
    }

    if (!epreuve || !epreuve.idEpreuve) {
      console.error("Erreur : objet épreuve invalide", epreuve);
      alert("Erreur : impossible de télécharger l'épreuve");
      return;
    }

    setDownloading(prev => ({ ...prev, [epreuve.idEpreuve]: true }));
    toast.info('Le téléchargement a commencé.');

    try {
      console.log('Tentative d\'enregistrement du téléchargement:', epreuve.idEpreuve);
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
        console.log('Réponse du serveur:', result);
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
        console.log('Téléchargement du fichier depuis:', downloadUrl);
        window.location.href = downloadUrl;
      } else {
        alert("Le fichier PDF n'est pas disponible pour cette épreuve.");
      }
      setDownloading(prev => ({ ...prev, [epreuve.idEpreuve]: false }));
      toast.success('Le téléchargement est terminé.');
    }
  };

  const handleCardClick = (epreuve) => {
    setSelectedEpreuve(epreuve);
  };

  const handleCloseDialog = () => {
    setSelectedEpreuve(null);
  };

  const sliderSettings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 5,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          initialSlide: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  };

  return (
    <div className="epreuves-container">
      <ToastContainer />
      <h2>Dernièrement ajoutées</h2>
      <Slider {...sliderSettings}>
        {filteredEpreuves.map((epreuve) => (
          <div className="epreuve-card" key={epreuve.idEpreuve} onClick={() => handleCardClick(epreuve)}>
            <img src={epreuve.image || imagesss} alt={epreuve.matiere} className="epreuve-image" />
            <div className="epreuve-details">
              <h3>{epreuve.matiere}</h3>
              <p>{epreuve.annee}</p>
              <p>{epreuve.typeEp}</p>
              <div className="epreuve-actions">
                <button 
                  onClick={(e) => { 
                    e.stopPropagation(); 
                    console.log("Fichier à télécharger :", epreuve.file_pdf);
                    handleDownload(epreuve); 
                  }}
                  disabled={downloading[epreuve.idEpreuve]}
                >
                  {downloading[epreuve.idEpreuve] ? 'Téléchargement...' : 'Télécharger PDF'}
                </button>
              </div>
            </div>
          </div>
        ))}
      </Slider>

      {selectedEpreuve && (
        <div className="dialog-overlay" onClick={handleCloseDialog}>
          <div className="dialog-content" onClick={(e) => e.stopPropagation()}>
            <img src={selectedEpreuve.image || imagesss} alt={selectedEpreuve.matiere} className="dialog-image" />
            <h3>{selectedEpreuve.matiere}</h3>
            <p>{selectedEpreuve.annee}</p>
            <p>{selectedEpreuve.typeEp}</p>
            <div className="epreuve-actions">
              <button 
                onClick={() => handleDownload(selectedEpreuve)}
                disabled={downloading[selectedEpreuve.idEpreuve]}
              >
                {downloading[selectedEpreuve.idEpreuve] ? 'Téléchargement...' : 'Télécharger PDF'}
              </button>
            </div>
            <button className="close-dialog" onClick={handleCloseDialog}>Fermer</button>
          </div>
        </div>
      )}
    </div>
  );
};

export default Epreuves;
