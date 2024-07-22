import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation, Navigate } from 'react-router-dom';
import { Link as ScrollLink, Element } from 'react-scroll';
import './App.css';
import Menu from './Menu';
import Moncompte from './compte';
import Universites from './Universites';
import Login from './composants/login';
import LoginUni from './composants/loginUni';
import Signup from './composants/Signup';
import SignupUni from './composants/SignupUni';
import icoEp from './icoEp.webp';
import etudie from './Infosection.jpg';
import CompteEn from './compte-en';
import CompteAd from './compte-ad';
import Epreuves from './Epreuves';
import etudiants from './epreuvelogo.png';
import sis from './image2.png';
import lis from './image3.jpg';
import EEtudiants from './etudiants.jpg';
import Compte from './compte';
import Footer from './Footer';
import Solutions from './Solutions';
import InscriptionEtudiant from './composants/InscriptionAp';
import Preloader from './Preloader';
import Slider from 'react-slick';
import "slick-carousel/slick/slick.css"; 
import "slick-carousel/slick/slick-theme.css";
import Catalogue from './Catalogue';
import { AuthProvider } from './AuthContext';

const epreuvesData = [
  { id: 1, nom: 'Epreuve 1', description: 'Epreuves de EPAC 2022', image: icoEp , url: 'url_de_l_epreuve_1', matiere: 'Mathematics', annee: '2022' },
  { id: 2, nom: 'Epreuve 2', description: 'Description de l\'épreuve 2', image: etudiants, url: 'url_de_l_epreuve_2', matiere: 'Physics', annee: '2021' },
  // Ajoutez d'autres épreuves ici
];

function SectionInfo() {
  return (
    <div className="sectionInfo" id="SectionInfo-section">
      <div className="sectionInfo-text">
        <h2>À propos de notre plateforme</h2>
        <p>
          Bienvenue sur notre plateforme de <span>banque d'épreuves en ligne !</span> Nous offrons une vaste gamme de ressources éducatives, notamment les <span>anciennes épreuves,</span> pour vous aider à exceller dans vos études. Que vous prépariez des examens ou que vous cherchiez à approfondir vos connaissances, notre plateforme est conçue pour répondre à vos besoins.
        </p>
        <p>
          Nos fonctionnalités incluent des épreuves passées, leurs corrigés, et bien plus encore. Rejoignez notre communauté d'apprenants et commencez à améliorer vos <span>performances académiques dès aujourd'hui !</span>
        </p>
      </div>
      <div className="sectionInfo-image">
        <img src={etudie} alt="Étudiants" />
      </div>
    </div>
  );
}

const slides = [
  {
    image: EEtudiants , 
    text: 'Améliorez vos performances avec les épreuves passées à portée de main.'
  },
  {
    image: sis, 
    text: '"Un catalogue riche et des épreuves à jour." - [Samirath]'
  },
  {
    image: lis , 
    text: '"La meilleure plateforme pour préparer mes examens!" - [Will Salem]'
  }
];

function Section1() {
  const settings = {
    dots: true,
    infinite: true,
    speed: 1000,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 4000
  };

  return (
    <div className="Section1">
      <Slider {...settings}>
        {slides.map((slide, index) => (
          <div key={index} className="slide">
            <div
              className="slide-image"
              style={{ backgroundImage: `url(${slide.image})` }}
            >
              <div className="overlay">
                <h1><span>P</span>ast<span>P</span>apers</h1>
                <div className="transparent-block">
                  <h4>{slide.text}</h4>
                  <ScrollLink to="epreuves-section" smooth={true} duration={500}>
                    <button>PARCOURIR</button>
                  </ScrollLink>
                </div>
              </div>
            </div>
          </div>
        ))}
      </Slider>
    </div>
  );
}

function Home({ searchCriteria }) {
  return (
    <>
      <Section1 />
      <SectionInfo />
      <Element name="epreuves-section">
        <Epreuves epreuves={epreuvesData} limit={7} searchCriteria={searchCriteria} />
      </Element>
      <Solutions />
      <Footer />
    </>
  );
}

function MainApp({ isAuthenticated, setAuthenticated, handleSearch }) {
  const [loading, setLoading] = useState(true);
  const location = useLocation();
  const limit = 12;
  const isHomePage = location.pathname === '/';
  const isCataloguePage = location.pathname === '/catalogue';

  const [searchCriteria, setSearchCriteria] = useState({ matiere: '', annee: '' });

  const onSearch = (criteria) => {
    setSearchCriteria(criteria);
    handleSearch(criteria);
  };

  useEffect(() => {
    // Simuler un délai de chargement pour la démonstration
    const timer = setTimeout(() => {
      setLoading(false);
    }, 1000); // Par exemple, 2 secondes de délai

    return () => clearTimeout(timer);
  }, []);

  if (loading) {
    return <Preloader />;
  }

  return (
    <div className="App">
      {(isHomePage || isCataloguePage) && <Menu onSearch={onSearch} />}
      <Routes>
        <Route path="/" element={<Home searchCriteria={searchCriteria} />} />
        <Route path="/login" element={<Login setAuthenticated={setAuthenticated} />} />
        <Route path="/loginUni" element={<LoginUni setAuthenticated={setAuthenticated} />} />
        <Route path="/inscription" element={<Signup />} />
        <Route path="/inscriptionUni" element={<SignupUni />} />
        <Route path="/catalogue" element={<Catalogue />} />
        <Route
          path="/compte"
          element={isAuthenticated ? <Moncompte /> : <Navigate to="/login" replace />}
        />
        <Route path="/universites" element={isAuthenticated ? <Universites /> : <Navigate to="/loginUni" replace />} />
        <Route path="/compte-en" element={<CompteEn limit={limit} />} />
        <Route path="/compte-ad" element={<CompteAd />} />
        <Route path="/compte" element={<Compte />} />
        <Route path="/InscriptionAp" element={<InscriptionEtudiant />} />
      </Routes>
    </div>
  );
}

function App() {
  const [isAuthenticated, setAuthenticated] = useState(false);

  const handleSearch = (criteria) => {
    // Handle search criteria logic here if needed
    console.log("Search criteria:", criteria);
  };

  return (
    <AuthProvider>
    <Router>
      <MainApp isAuthenticated={isAuthenticated} setAuthenticated={setAuthenticated} handleSearch={handleSearch} />
    </Router>
    </AuthProvider>
  );
}

export default App;
