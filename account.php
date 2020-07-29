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


// Fonction cherche utilisateur
function SearchUser ($bdd, $userName) {

  $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
  $req->bindValue(1, $userName);
  $req->execute();
  $dataAccount = $req->fetch();
  $req->closeCursor();

  return $dataAccount;
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


//  Fonction message erreur
$erreur = '';
function MessageError($erreur)
{

  if (isset($erreur)) {

    echo  $erreur;
    
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