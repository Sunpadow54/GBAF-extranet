<?php

session_start();

/* Base de donnée connexion :*/
try
{
    $bdd= new PDO(
        'mysql:host=localhost;
        dbname=gbaf-extranet;
        charset=utf8',
        'root',
        '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
}
catch (Exception $e)
{
    die(' Erreur : ' . $e->getMessage());
}


// message si la personne viens de s'inscrire
if (isset($_SESSION['username']))
{
  $welcome = 'Vous pouvez vous connecter';
}

/*--------------------------- Verification User */
if (isset($_POST['username']) && isset($_POST['password']))
{
  //Cherche l'username et compare à la BDD
  $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
  $req->execute(array($_POST['username']));
  $dataAccount = $req->fetch();
  $req->closeCursor();

  // Verification mot de passe (Hashé)
  $isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);

  if ($_POST['username'] == $dataAccount['username'] && $isPasswordCorrect)
  {  

    // Connexion
    $_SESSION['nom'] = htmlspecialchars($dataAccount['nom']);
    $_SESSION['prenom'] = htmlspecialchars($dataAccount['prenom']);
    $_SESSION['id_user'] = $dataAccount['id_user'];

    header('Location: accueil.php');

  }
  else
  {
      header('Location: index.php');
  }
}
?>


<!-- ---------------------------Formulaire Connexion -->      
<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8" />
      <title> GBAF extranet - connexion</title>

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

        <span class="message"> 
          <?php 
          if (isset($_SESSION['username']))
          {
            echo $welcome ;
          }
          ?>
        </span>

        <form method="post"action="index.php">
          <p>

              <label for="pseudo">Identifiant :</label>
              <input type="text" id="pseudo" name="username" required 
              <?php

              if (isset($_SESSION['username']) && !isset($connexionSubmit))
              {
                echo 'value ="' .$_SESSION['username'].'"';
              }

              elseif (isset($connexionSubmit))
              {
                echo 'value ="' .$_POST['username'].'"';
              }
              ?>
              >


              <label for="mp">Mot de passe :</label>
              <input type="password" id="mp" name="password"required>


              <input class="button-envoyer" type="submit" name ='connexionSubmit' value="Connexion">


              <span>Les champs indiqués par une * sont obligatoires</span>

          </p>
        </form>

        <a href="mp.php"> mot de passe oublié ?</a>

        <a href="inscription.php">créer un compte</a>

      </fieldset>
    </main>
  </body>
</html>