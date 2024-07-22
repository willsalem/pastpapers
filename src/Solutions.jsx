import React from 'react';
import { motion } from 'framer-motion';
import './Solutions.css';

const solutions = [
  {
    title: "Accès simplifié aux épreuves archivés",
    description: " Grâce à notre plateforme, chaque étudiant bénéficie d'un accès simplifié et immédiat aux épreuves archivées, indépendamment de leur statut ou de leur établissement, facilitant ainsi une préparation optimale aux examens.",
    icon: "🔍",
  },
  {
    title: "Téléchargement gratuit et illimité",
    description: "Notre service permet le téléchargement gratuit et illimité des épreuves, supprimant ainsi toute barrière financière et offrant à chaque utilisateur la possibilité de s'instruire sans contrainte.",
    icon: "⬇️",
  },
  {
    title: "Ressources pédagogiques de qualité",
    description: "Notre plateforme met à disposition des ressources pédagogiques de haute qualité, sélectionnées et organisées pour maximiser l'efficacité de l'apprentissage et favoriser la réussite académique",
    icon: "📚",
  },
  {
    title: "Quiz interactifs pour auto-évaluation(A venir)",
    description: " Dans le cadre de notre vision à long terme, nous envisageons d'intégrer des quiz interactifs à notre plateforme qui permettront aux étudiants de s'auto-évaluer de manière ludique et efficace.",
    icon: "📝",
  },
];

const Solutions = () => {
  return (
    <section id="solutions-section" className="solutions-section">
      <h2 className="section-title">Solutions</h2>
      <div className="solutions-container">
        {solutions.map((solution, index) => (
          <motion.div
            className="solution-card"
            key={index}
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.9 }}
          >
            <div className="solution-icon">{solution.icon}</div>
            <div>
              <h3 className="solution-title">{solution.title}</h3>
              <p className="solution-description">{solution.description}</p>
            </div>
          </motion.div>
        ))}
      </div>
    </section>
  );
};

export default Solutions;
