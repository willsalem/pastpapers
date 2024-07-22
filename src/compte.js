import React from 'react';
import { Route, Routes } from 'react-router-dom';
import Dashboard from './composants/Dashboard';
import Sidebar from './composants/Sidebar';
import Header from './composants/Header';
import './Moncompte.css';

function Compte() {
  return (
    
      <div className="Moncompte">
        <Sidebar />
        <div className="main-content">
          <Header />
          <Routes>
            <Route path="/compte" exact component={Dashboard} />
          </Routes>
        </div>
      </div>
    
  );
}

export default Compte;










/*import React from 'react'

function Inscription() {
  return (
    <div>
        <div>
            <input type="radio" class="tabs__button" name="Inscription" id="InscriptionEn" checked />
                <label class="tabs__text" for="InscriptionEn">Informations personnelles</label>
                <div class="tabs__content">
                    Se connecter en tant que : <a href="./formulaireAdmin.js">Administrateur</a> - <a href="./formulaireEnseign.js">Enseignant</a>
                    <h1>Bienvenue</h1>
                    <form class="form" id="form1" action="Sami.php" method="post">
                        <div class="input-group">
                            <input class="input-group__input" type="text" placeholder="&nbsp;" name="nom" id="nom" autocomplete="off" required />
                            <label class="input-group__label" for="nom">*Nom</label>
                        </div>
                        <div class="input-group">
                            <input class="input-group__input" type="text" name="prenom" placeholder="&nbsp;" id="prénom" required />
                            <label class="input-group__label" for="prenom">*Prénom</label>
                        </div>
                        <div class="input-group">
                            <input class="input-group__input" type="date" name="date_naissance" placeholder="&nbsp;" id="date_naissance" required />
                            <label class="input-group__label" for="date_naissance">*Date de naissance</label>
                        </div>
                        <div class="input-group">
                            <input class="input-group__input" type="text" name="sexe" placeholder="&nbsp;" id="sexe" required />
                            <label class="input-group__label" for="sexe">*Sexe</label>
                        </div>
                        <div class="input-group">
                            <input class="input-group__input" type="text" name="nationalite" placeholder="&nbsp;" id="nationalite" required />
                            <label class="input-group__label" for="password">*Mot de passe</label>
                        </div>
                        <div class="input-group">
                            <input class="input-group__input" type="text" name="nationalite" placeholder="&nbsp;" id="nationalite" required />
                            <label class="input-group__label" for="email">*Email</label>
                        </div>

                            <p>*Informations sur le niveau d'étude</p>
                            <div class="input-group">
                                <input class="input-group__input" type="text" name="ins_réins" placeholder="&nbsp;" id="ins_réins"  />
                                <label class="input-group__label" for=".....">Grade</label> 
                            </div>
                            <div class="input-group">
                                <input class="input-group__input" type="text" name="statut" placeholder="&nbsp;" id="statut"  />
                                <label class="input-group__label" for="statut">Statut(AME/APE/Aspirant...)</label> 
                            </div>
                            <button type="submit">Envoyer</button> 
                    </form>            
                    </div>

                    <script>
                            const queryString = window.location.search
                            const urlParams = new URLSearchParams(queryString)
                            const sign = urlParams.get('InscriptionEn')
                            if(sign == 'yes') {
                                alert("Inscription réussie")
                            } else 
                                const msg = urlParams.get('msg')
                                alert(msg)
                    </script>
        </div>
    </div>
  );
}




function Moncompte(){
    return(
        <div>
            <Inscription />
        </div>
    )
}


export default Moncompte();*/