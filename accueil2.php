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
    <fieldset class="connexion">

      <form method="post"action="connexion.php">
        <img src="../images/GBAF.png" alt="Logo GBAF">
        <p>
            <label for="pseudo">Identifiant :</label>
            <input type="text" id="pseudo" name="identifiant">

            <label for="mp">Mot de passe :</label>
            <input type="text" id="mp" name="mot-de-passe">

            <input type="submit" value="Connexion">
        </p>
      </form>
    </fieldset>
  </body>
</html>