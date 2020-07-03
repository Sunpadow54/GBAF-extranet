<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title> GBAF extranet</title>

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" media="all and (min-device-width : 320px) and (max-device-width : 480px)" href="style-mobile.css">
        <link rel="stylesheet" media="all and (max-device-width: 1280px ) and (min-device-width: 481px)" href="style-tablette.css">

        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;900&display=swap" rel="stylesheet">  
    </head>
    
  <body>

    <main class="inscription-connexion">
      <fieldset>
          <legend>Créer un compte :</legend>
          <img src="../images/GBAF.png" alt="Logo GBAF">
          <form method="post" action="">
            <p>
                  <label for="pseudo">Identifiant :</label>
                  <input type="text" id="pseudo" name="username" required>

                  <label for="mp">Mot de passe :</label>
                  <input type="password" id="mp" name="password" required>

                  <label for="nom">Nom :</label>
                  <input type="text" id="nom" name="nom">

                  <label for="prenom">Prénom :</label>
                  <input type="text" id="prenom" name="prenom" required>
              
                  <label for="question">Votre question secrète :</label>
                  <input type="textarea" id="question" name="question" required>

                  <label for="reponse">La réponse à votre question :</label>
                  <input type="textarea" id="reponse" name="reponse" required>

                  <input type="submit" value="Envoyer">
            </p>
          </form>

          <a href="connexion.php">se connecter</a>
      </fieldset>
      
    </main>
  </body>
</html>