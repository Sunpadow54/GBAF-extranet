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




?>