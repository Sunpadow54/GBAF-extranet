<?php
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
        $req = $bdd->prepare('SELECT username, password FROM account');
        $req->execute();
        $donnees = $req->fetch();
        if ($_POST['username'] == $donnees['username'] && $_POST['password'] == $donnees['password'])
        {
            header('Location: index.php');
        }
        else
        {
            header('Location: index2.php');
        }
    }
else
{
    header('Location: index2.php');
}

?>