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

/*--------------------------- Verification User */

if (isset($_POST['username']) && isset($_POST['password']))
    {
        //cherche et compare à la BDD
        $req = $bdd->prepare('SELECT * FROM account WHERE username= ?');
        $req->execute(array($_POST['username']));
        $donnees = $req->fetch();

        if ($_POST['username'] == $donnees['username'] && $_POST['password'] == $donnees['password'])
        {  
            $_SESSION['nom'] = $donnees['nom'];
            $_SESSION['prenom'] = $donnees['prenom'];
            
            $req->closeCursor();
            
            header('Location: accueil.php');
        }
        else
        {
            header('Location: index.php');
        }
    }
else
    {
        header('Location: index.php');
    }

?>