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

?>

<?php



if (isset($_POST['username']))
{
  // Verifie si le Username n'existe pas déjà
  $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
  $req->execute(array($_POST['username']));
  $dataAccount = $req->fetch();
  $req->closeCursor();
    
    if  ($dataAccount)
    {
        $_SESSION['username'] = $_POST['username'];
        $formQuestion =  '<label for="reponse">' .$dataAccount['question']. '? </label>
                        <input type="textarea" id="reponse" name="reponse">
                        ';

/*         if  (isset($_POST['reponse']) && $_POST['reponse'] == $dataAccount['reponse'])
        {
            $label =  'bravo';
            $input = 'yeahh';
        }
        elseif (isset($_POST['reponse']) && $_POST['reponse'] != $dataAccount['reponse'])
        {
            $erreur = ' pas bonne réponse';
        } */
    }
    
    else
    {
        $erreur = 'cet identifiant n\'existe pas';

        $formUsername =    '<label for="pseudo">Identifiant : </label>
                            <input type="text" id="pseudo" name="username" size="20">
                            ';
    }
}

elseif (isset($_SESSION['username']))
{
    $req2 = $bdd->prepare('SELECT * FROM account WHERE username = ?');
    $req2->execute(array($_SESSION['username']));
    $dataAccount2 = $req2->fetch();
    $req2->closeCursor();

    if  (isset($_POST['reponse']) && $_POST['reponse'] == $dataAccount2['reponse'])
    {
        $test = 'bravo <br> yeaa!';
    }
    elseif (isset($_POST['reponse']) && $_POST['reponse'] != $dataAccount2['reponse'])
    {
        $erreur = ' pas bonne réponse';
        
    }
}

else
{
    $formUsername =    '<label for="pseudo">Identifiant : </label>
                        <input type="text" id="pseudo" name="username" size="20">
                        ';
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
                if (isset($formQuestion))
                {
                    echo $formQuestion;
                }
                elseif (isset($test))
                {
                    echo $test;
                }
                elseif  (isset($formUsername))
                {
                    echo $formUsername;
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
