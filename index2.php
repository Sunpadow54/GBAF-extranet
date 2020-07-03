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
        <legend>Se connecter :</legend>
        <img src="../images/GBAF.png" alt="Logo GBAF">
        <form method="post"action="connexion.php">
          <p>
              <label for="pseudo">Identifiant :</label>
              <input type="text" id="pseudo" name="username" required>

              <label for="mp">Mot de passe :</label>
              <input type="password" id="mp" name="password"required>

              <input class="connexion-button" type="submit" value="Connexion">
          </p>
        </form>

        <a href=""> mot de passe oublié ?</a>
        <a href="profil.php">créer un compte</a>
      </fieldset>

    </main>
  </body>
</html>