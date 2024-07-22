import React from 'react'
import './Footer.css'

export default function Footer() {
    return (
        <footer className="footer">
          <div className="footer-content">
            <p>&copy; {new Date().getFullYear()} PastPapers. Tous droits réservés. Développé par <span>Will Salem</span> & <span>Samirath</span> </p>
            
          </div>
        </footer>
      );
    };