// AuthContext.js
import React, { createContext, useContext, useState } from 'react';
import { toast } from 'react-toastify';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);

  const login = (userData) => {
    setUser(userData);
    // Ajoutez une notification pour indiquer que l'utilisateur s'est connecté
    toast.success(`Connexion réussie. Bienvenue, ${userData.user.prenom} ${userData.user.nom}`);
  };

  const logout = () => {
    setUser(null);
    // Ajoutez une notification pour indiquer que l'utilisateur s'est déconnecté
    toast.info('Vous vous êtes déconnecté.');
  };

  return (
    <AuthContext.Provider value={{ user, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => useContext(AuthContext);
