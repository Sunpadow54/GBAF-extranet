<?php
session_start();

/* Base de donnée connexion :*/

try
{
    $bdd = new PDO(
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
    die('Erreur : ' . $e->getMessage());
}
?>

<!-- FORMULAIRE D'INSCRIPTION -->

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

          <form method="post" action="inscription">
            <p>
                  <label for="pseudo">Identifiant : </label>
                  <input type="text" id="pseudo" name="username" size="20" required>

                  <label for="mp">Mot de passe : </label>
                  <input type="password" id="mp" name="password" size="20" required>

                  <label for="nom">Nom : </label>
                  <input type="text" id="nom" name="nom" size="30"required>

                  <label for="prenom">Prénom : </label>
                  <input type="text" id="prenom" name="prenom" size="30" required>
              
                  <label for="question">Votre question secrète : </label>
                  <input type="textarea" id="question" name="question" required>

                  <label for="reponse">La réponse à votre question : </label>
                  <input type="textarea" id="reponse" name="reponse" required>

                  <input class="button-envoyer" type="submit" value="Envoyer">
                  
                  <span>Les champs indiqués par une * sont obligatoires</span>
            </p>
          </form>

          <a href="connexion.php">se connecter</a>
      </fieldset>
      
    </main>
  </body>
</html>


<!-- VERIFICATION & AJOUT NOUVEL UTILISATEUR -->

<?php

if (isset($_POST))
{
  // Verification que tous les champs sont remplis
  if (!empty($_POST['nom']) && !empty($_POST['prenom']) 
  && !empty($_POST['username']) && !empty($_POST['password']) 
  && !empty($_POST['question']) && !empty($_POST['reponse'])) 
  {

    // Verification que l'identifiant n'existe pas
    $req = $bdd->prepare('SELECT username FROM account WHERE username = ?');
    $req->execute(array($_POST['username']));
    $dataAccountUsername = $req->fetch();
    if ( $dataAccountUsername)
    {
      // Identifiant existe déjà
      $req->closeCursor();
      echo 'Cet identifiant existe déjà';
    }

    else
    {
      $req->closeCursor();
      // Hashage du mot de passe
      $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
      // Insère le nouvel Utilisateur dans la BDD
      $req2 = $bdd->prepare('INSERT INTO account (nom, prenom, username, password, question, reponse) VALUES (:nom, :prenom, :username, :password, :question, :reponse)');
      $req2->execute(array(
        'nom' => htmlspecialchars($_POST['nom']),
        'prenom' => htmlspecialchars($_POST['prenom']),
        'username' => htmlspecialchars($_POST['username']),
        'password' => $passwordHashed,
        'question' => htmlspecialchars($_POST['question']),
        'reponse' => htmlspecialchars($_POST['reponse'])
      ));
      $req2->closeCursor();

      header('Location: index.php');
    }
  
  }

  else
  {
  echo 'il vous manque un champ à remplir';
  }
}

?>