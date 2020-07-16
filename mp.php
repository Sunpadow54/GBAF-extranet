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



// Formulaires 
$formDefault = '<label for="pseudo">Identifiant : </label>
      <input type="text" id="pseudo" name="username" size="20">
       ';
$formQuestion ='
      <input type="textarea" id="reponse" name="reponse">';
$fomPasswordChange = '<label for="mp">Nouveau mot de passe : </label>
      <input type="password" id="mp" name="password" size="20">';

$formType = $formDefault;


// Si on envoie son username on récupère session username
if (isset($_POST['username']))
{
  $_SESSION['username'] = $_POST['username'];
}

// Si on viens d'arriver sur la page, on efface toutes les Sessions existantes (securité)
elseif (!isset($_POST['dataPosted']))
{
  $_SESSION = array();
  session_destroy();
}


// -------------------------------- Si Session username existe --------------------------------
if (isset($_SESSION['username']))
{

  // Vérifie si le Username existe dans la Bdd
  $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
  $req->execute(array($_SESSION['username']));
  $dataAccount = $req->fetch();
  $req->closeCursor();

  // ------------------------------ Si l'utilisateur existe
  if  ($dataAccount)
  { 
    
    // On donne la question
    $formType = $formQuestion;
    $questionUser = '<label for="reponse">' . $dataAccount['question'] . ' </label>';
    $erreur = 'répondez à votre question secrète : ';
    
  }
  else
  {
      $erreur = 'cet identifiant n\'existe pas';
  }



  // ------------------------------ Si on envoie une réponse
  if  (isset($_POST['reponse']))
  {
    // 
    $questionUser = $dataAccount['question'];
    $formType = $formQuestion;
    
    $erreur = 'répondez à votre question secrète : ';

    // Si la réponse correspond
    if  ($_POST['reponse'] == $dataAccount['reponse'])
    {

      unset($questionUser);

      // On affiche le formulaire de changement de mot de passe
      $formType = $fomPasswordChange;
      $erreur = 'Vous pouvez changer votre mot de passe : ';

    }
    // Mais si c'est une mauvaise réponse
    else
    {
      $erreur = 'Ce n\'est pas la réponse attendue';
    }
  }

  // ------------------------------ Si on envoie un nouveau mot de passe
  if  (isset($_POST['password']))
  {
    
    unset($questionUser);

    $formType = $fomPasswordChange;

    // Si le nouveau mot de passe est conforme
    if  (preg_match( "#(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z.-_]{4,}#", $_POST['password'] ))
    {

      // On Hash le mot de passe
      $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

      // On remplace le mp dans la base de données
      $req2 = $bdd->prepare('UPDATE account SET
                            password = :password
                            WHERE username = :username
                          ');
      $req2->execute  (array(
          'password' => $passwordHashed,
          'username' => $_SESSION['username']
      ));

      $req2->closeCursor();

      $_SESSION['messagePWchanged'] = 'Votre mot de passe à bien été changé . Vous pouvez vous connecter';

      header('Location: index.php');

    }

    // Si le mot de passe n'est pas conforme
    else
    {
      $erreur ="le mot de passe doit contenir au moins 4 caractères, dont une minuscule, une majuscule et un chiffre";
    }

  }

}



?>


<!-- ---------------------------Formulaire Connexion -->      
<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8" />
      <title> GBAF extranet - oubli mot de passe</title>

      <link rel="stylesheet" href="style.css">
      <link rel="stylesheet" media="all and (min-device-width : 320px) and (max-device-width : 480px)" href="style-mobile.css">
      <link rel="stylesheet" media="all and (max-device-width: 1280px ) and (min-device-width: 481px)" href="style-tablette.css">

      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;900&display=swap" rel="stylesheet">  
  </head>

  <body>
    <main class="inscription-connexion">
      <fieldset>

        <legend>Mot de passe oublié ?</legend>

        <img src="../images/GBAF.png" alt="Logo GBAF">

          <?php 
          //message erreur
          if (isset($erreur))
          {
            echo '<span class="message-erreur">' . $erreur .' </span>'; 
          }
          ?>


        <form method="post"action="mp.php">
          <p>

            <?php
              if  (isset($questionUser))
              {
                echo $questionUser;
              }
              // Affiche le formulaire adéquat
              echo $formType;
            ?>

              <input class="button-envoyer" type="submit" name="dataPosted" value="Envoyer">

          </p>
        </form>

        <a href="index.php">Connexion</a>

      </fieldset>
    </main>
  </body>
</html>
