import React, { useState } from 'react';
import EpreuvesGrid from './EpreuvesGrid';
import Filter from './Filtre'; // Assurez-vous de créer ce composant pour gérer les filtres
import './catalogue.css';

const Catalogue = () => {
  const [searchCriteria, setSearchCriteria] = useState({});
  
  const handleFilterChange = (criteria) => {
    setSearchCriteria(criteria);
  };

  return (
    <div className="catalogue-page">
      <div className="filter-section">
        <Filter onChange={handleFilterChange} />
      </div>
      <div className="epreuves-section">
        <EpreuvesGrid searchCriteria={searchCriteria} limit={10} />
      </div>
    </div>
  );
};

export default Catalogue;
