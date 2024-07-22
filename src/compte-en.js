import React, { useState, useEffect } from 'react';
import { Route, Routes } from 'react-router-dom';
import Dashboard from './composants/Dashboard';
import Sidebar from './composants/SidebarEn';
import Header from './composants/Header';
import './compte-en.css';
import Telechargement from './composants/Telechargement';
import staticLogo from './epreuvelogo.png';
import Preloader from './Preloader';

function CompteEn({ limit }) {
  const [isFormVisible, setFormVisible] = useState(false);
  const [matiere, setMatiere] = useState('');
  const [annee, setAnnee] = useState('');
  const [typeEp, setTypeEp] = useState('');
  const [epreuves, setEpreuves] = useState([]);
  const [file_pdf, setFile_pdf] = useState(null);

  useEffect(() => {
    // Fonction pour récupérer les épreuves
    const fetchEpreuves = async () => {
      try {
        const response = await fetch(`http://localhost/backend/api/epreuve.php?limit=${limit}`);
        console.log(response); // Ajout du log pour afficher la réponse
        if (!response.ok) {
          throw new Error('Erreur lors de la récupération des épreuves');
        }
        const data = await response.json();
        console.log(data); // Ajout du log pour afficher les données récupérées
        setEpreuves(data.data.epreuves); // Mettre à jour l'état avec les données récupérées
      } catch (error) {
        console.error('Erreur lors de la récupération des épreuves:', error);
        alert('Erreur lors de la récupération des épreuves');
      }
    };

    // Appeler la fonction pour récupérer les épreuves au chargement du composant
    fetchEpreuves();
  }, [limit]);

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    const allowedExtensions = /(\.pdf)$/i; // Regex pour vérifier si l'extension est .pdf

    if (!file || !allowedExtensions.exec(file.name)) {
      setFile_pdf(null);
      alert("Veuillez sélectionner un fichier PDF.");
      return;
    }

    setFile_pdf(file);
  };

  const handleAddButtonClick = () => {
    setFormVisible(true);
  };

  const handleFormClose = () => {
    setFormVisible(false);
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('matiere', matiere);
    formData.append('annee', annee);
    formData.append('typeEp', typeEp);
    formData.append('file_pdf', file_pdf);
    formData.append('action', 'ADD_EPREUVE');

    try {
      const response = await fetch('http://localhost/backend/api/enseignant.php?action=add_epreuve', {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        throw new Error('Erreur lors de l\'ajout de l\'épreuve');
      }

      const contentType = response.headers.get('Content-Type');
      if (contentType && contentType.includes('application/json')) {
        const result = await response.json();
        setEpreuves([...epreuves, result]);
        alert('Épreuve ajoutée avec succès');
      } else {
        throw new Error('La réponse du serveur n\'est pas du JSON');
      }
    } catch (error) {
      console.error('Erreur lors de l\'ajout de l\'épreuve:', error);
      alert('Erreur lors de l\'ajout de l\'épreuve');
    }

    setMatiere('');
    setAnnee('');
    setTypeEp('');
    setFile_pdf(null);
    setFormVisible(false);
  };

  const [loading, setLoading] = useState(true);
  useEffect(() => {
    // Simuler un délai de chargement pour la démonstration
    const timer = setTimeout(() => {
      setLoading(false);
    }, 500); // Par exemple, 2 secondes de délai

    return () => clearTimeout(timer);
  }, []);

  if (loading) {
    return <Preloader />;
  }

  return (
    <div className="Moncompte">
      <Sidebar />
      <div className="main-content">
        <Header />
        <div className="dashboard">
          {epreuves.map((epreuve, index) => (
            <div key={index} className="SALEMM">
              <img src={staticLogo} alt="Étudiants" className="epreuve-image" />
              <div className="epreuve-details">
                <h3>{epreuve.matiere}</h3>
                <p>{epreuve.annee}</p>
                <a href={epreuve.file_pdf} download className='Jessica'>Télécharger</a>
              </div>
            </div>
          ))}
        </div>
        <Routes>
          <Route path="/compte-en/*" element={<Dashboard />} />
          <Route path="/compte-en/Telechargement" element={<Telechargement />} />
        </Routes>
        <button className="add-button" onClick={handleAddButtonClick}>+</button>
        {isFormVisible && (
          <div className="form-overlay">
            <form className="add-form" onSubmit={handleFormSubmit}>
              <button type="button" className="close-button" onClick={handleFormClose}>X</button>
              <h2>Ajouter épreuves</h2>
              <div className="static-logo-container">
                <img src={staticLogo} alt="Logo" className="static-logo" />
              </div>
              <input
                type="text"
                placeholder="Matière"
                value={matiere}
                onChange={(e) => setMatiere(e.target.value)}
                required
              />
              <select
                value={annee}
                onChange={(e) => setAnnee(e.target.value)}
                required
              >
                <option value="">Sélectionnez une année</option>
                {Array.from({ length: 50 }, (_, i) => (
                  <option key={i} value={2024 - i}>{2024 - i}</option>
                ))}
              </select>
              <select
                  name='typeEp'
                  
                    value={typeEp}
                    onChange={(e) => setTypeEp(e.target.value)}
                    required // Ajoute l'attribut "required"
                  >
                    <option value="">Sélectionnez le type d'épreuve</option>
                    <option value="session_normale">Session normale</option>
                    <option value="session_rattrapage">Session de rattrapage</option>
                  </select>

              <input
                type="file"
                accept=".pdf"
                onChange={handleFileChange}
              />
              <button type="submit">Enregistrer</button>
            </form>
          </div>
        )}
      </div>
    </div>
  );
}

export default CompteEn;
