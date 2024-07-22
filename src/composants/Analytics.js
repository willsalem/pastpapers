import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Bar, Line } from 'react-chartjs-2';
import 'chart.js/auto';
import { FaSchool, FaDownload, FaChalkboardTeacher, FaUserGraduate, FaUniversity } from 'react-icons/fa';
import './Analytics.css';

const Analytics = ({ detailed }) => {
  const [stats, setStats] = useState({
    epreuves: 0,
    enseignants: 0,
    telechargements: 0,
    apprenants: 0,
    universites: 0,
  });

  useEffect(() => {
    const fetchData = async () => {
      try {
        const responseUniversites = await axios.get('http://localhost/backend/api/universite.php');
        const responseEpreuves = await axios.get('http://localhost/backend/api/epreuve.php?NOMBRE_EPREUVE');
        const responseEnseignants = await axios.get('http://localhost/backend/api/enseignant.php'); // Assurez-vous que l'URL de l'API des enseignants est correcte
        const responseApprenants = await axios.get('http://localhost/backend/api/apprenant.php');
        const responseTelechargement = await axios.get('http://localhost/backend/api/telechargement.php');

        // Ajoutez des consoles pour vérifier les données reçues
        console.log('Universités:', responseUniversites.data);
        console.log('Épreuves:', responseEpreuves.data);
        console.log('Enseignants:', responseEnseignants.data);
        console.log('Apprenants:', responseApprenants.data);
        console.log('Téléchargements:', responseTelechargement.data);

        setStats({
          universites: responseUniversites.data.nbr_elements,
          epreuves: responseEpreuves.data.nbr_elements,
          enseignants: responseEnseignants.data.nbr_elements,
          apprenants: responseApprenants.data.nbr_elements,
          telechargements: responseTelechargement.data.nbr_elements
        });
      } catch (error) {
        console.error("Erreur lors de la récupération des statistiques", error);
      }
    };

    fetchData();
  }, []);

  const barChartData = {
    labels: ['Épreuves', 'Enseignants', 'Apprenants', 'Téléchargements', 'Universités'],
    datasets: [
      {
        label: 'Statistiques',
        data: [stats.epreuves, stats.enseignants, stats.apprenants, stats.telechargements, stats.universites],
        backgroundColor: ['#3498db', '#1abc9c', '#f39c12', '#e74c3c'],
      },
    ],
  };

  const lineChartData = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label: 'Téléchargements mensuels',
        data: [12, 19, 3, 5, 2, 3, 12, 5, 2, 3, 7, 10], // Replace this with real data if available
        fill: false,
        borderColor: '#3498db',
      },
    ],
  };

  return (
    <div className="analytics-container">
      <div className="analytics-card">
        <FaSchool className="analytics-icon" />
        <div className="analytics-value">{stats.epreuves}</div>
        <div className="analytics-label">Épreuves</div>
      </div>
      <div className="analytics-card">
        <FaChalkboardTeacher className="analytics-icon" />
        <div className="analytics-value">{stats.enseignants}</div>
        <div className="analytics-label">Enseignants</div>
      </div>
      <div className="analytics-card">
        <FaUserGraduate className="analytics-icon" />
        <div className="analytics-value">{stats.apprenants}</div>
        <div className="analytics-label">Apprenants</div>
      </div>
      <div className="analytics-card">
        <FaUniversity className="analytics-icon" />
        <div className="analytics-value">{stats.universites}</div>
        <div className="analytics-label">Universités</div>
      </div>
      {detailed && (
        <>
          <div className="analytics-card">
              <FaDownload className="analytics-icon" />
              <div className="analytics-value">{stats.telechargements}</div>
              <div className="analytics-label">Téléchargements</div>
          </div>
          <div className="chart-container">
            <Bar data={barChartData} />
          </div>
          <div className="chart-container">
            <Line data={lineChartData} />
          </div>
        </>
      )}
    </div>
  );
};

export default Analytics;
