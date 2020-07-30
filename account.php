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


// Fonction cherche l'utilisateur
function SearchUser ($bdd, $userName) {

  $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
  $req->bindValue(1, $userName);
  $req->execute();
  $dataAccount = $req->fetch();
  $req->closeCursor();

  return $dataAccount;
}

$message = 0;
switch ($message)
{
  case 0:
    echo '';
  break;

  case 1:
    echo "Cet identifiant n'existe pas";
  break;

  case 2:
      echo "Ce n'est pas le bon mot de passe";
  break;

  case 3:
    echo "le mot de passe doit contenir au moins 4 caractères, dont une minuscule, une majuscule et un chiffre";
  break;

  case 4:
    echo " Veuillez remplir tous les champs";


}

//  Fonction Affiche message erreur / message

function IsMessageError($message)
{

  if (isset($message)) {

    echo  $message;
    
  }

}


// Fonction garde en mémoire la value postée de l'username
function ValueInputUsername()
{

  if (isset($_SESSION['username']) && !isset($connexionSubmit)) {

    echo htmlspecialchars($_SESSION['username']);

  }

  elseif (isset($connexionSubmit))  {

    echo htmlspecialchars($_POST['username']);

  }

}

// FONCTION pour effacer les valeurs de session 
function UnsetPreviousSession()
{
    unset($_SESSION['username']);
}


function deleteSession()
{
  $_SESSION = array();
  session_destroy();
}
?>