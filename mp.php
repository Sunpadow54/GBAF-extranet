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



//Formulaire Default
$formUsername =    '<label for="pseudo">Identifiant : </label>
                    <input type="text" id="pseudo" name="username" size="20">
                    ';

// récupère session username
if (isset($_POST['username']))
{
  $_SESSION['username'] = $_POST['username'];
}                   

// Si Session username existe
if (isset($_SESSION['username']))
{

  // Vérifie si le Username existe dans la Bdd
  $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
  $req->execute(array($_SESSION['username']));
  $dataAccount = $req->fetch();
  $req->closeCursor();

  // Si Username existe
  if  ($dataAccount)
  {
      // on donne la question
      $formQuestion =  '<label for="reponse">' .$dataAccount['question']. '? </label>
                      <input type="textarea" id="reponse" name="reponse">
                      ';
      $erreur = "répondez à votre question secrète";

      // Si bonne réponse
      if  (isset($_POST['reponse']) && $_POST['reponse'] == $dataAccount['reponse'])
      {
        $erreur = 'bonne réponse';
      }

      //Si Mauvaise réponse
      elseif  ($_POST['reponse'] != $dataAccount['reponse'])
      {
        $erreur = 'mauvaise réponse';
      }

      // Sinon propose de répondre
      elseif (!isset($_POST['reponse']))
      {
        $erreur = "répondez à votre question secrète 2";
      }

  }

  else
  {
      $erreur = 'cet identifiant n\'existe pas';
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
                if  (isset($formUsername))
                {
                    echo $formUsername;
                }
                elseif (isset($formQuestion))
                {
                    echo $formQuestion;
                }
            ?>

              <input class="button-envoyer" type="submit" name="dataPosted" value="Envoyer">

          </p>
        </form>

        <a href="index.php">Connexion</a>

      </fieldset>
    </main>
  </body>
</html>
