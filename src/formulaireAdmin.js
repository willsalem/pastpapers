function FormulaireAdmin (){
    return(
        <div>
            <input type="radio" class="tabs__button" name="signForm" id="signIn" checked />
                <label class="tabs__text" for="signIn">Informations personnelles</label>
                <div class="tabs__content">
                    <h1>Bienvenue</h1>
                    <p style="color: black;">Connexion au tableau de bord</p>
                    <form class="form" id="form1" action="redirect.php" method="post">
                        <div class="input-group">
                            <input class="input-group__input" type="text" placeholder="&nbsp;" name="nom" id="nom" autocomplete="off" required />
                            <label class="input-group__label" for="nom">Nom</label>
                        </div>
                        <div class="input-group">
                            <input class="input-group__input" type="password" name="password" placeholder="&nbsp;" id="password" required />
                            <label class="input-group__label" for="password">Mot de passe</label>
                        </div>
                        <button type="submit">Envoyer</button> 
                    </form>            
                </div>
                <script>
        const queryString = window.location.search
        const urlParams = new URLSearchParams(queryString)
        const connect = urlParams.get('connect')
        if(connect == 'fail') {
            alert("Vous n'êtes pas un administrateur")
        }
    </script>
        </div>
    );
}


export default FormulaireAdmin();