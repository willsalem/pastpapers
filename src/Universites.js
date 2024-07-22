import Sidebar from "./composants/SidebarUni";
import React, { useState, useEffect } from 'react';
import Header from "./composants/Header";
import './compte-en.css'
import ListeEn from "./composants/ListeEnseignant";
import Preloader from './Preloader';


function Universités(){
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


    return(
        <div className="Moncompte">
            <Sidebar />
            <div className="main-content">
                <Header />  
                <div className="dashboard">
                    <ListeEn />
                </div>
            </div>
        </div>
    );
}

export default Universités; 