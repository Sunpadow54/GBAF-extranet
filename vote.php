<?php

session_start();

if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['id_user']))
{
    
    if (isset($_GET['vote']) && isset($_GET['id_acteur']))
    {

        // Base de donnée connexion : 
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


        // Cherche si l'user n'a pas déjà like/dislike
        $req = $bdd->prepare('SELECT * FROM vote WHERE id_user = ? AND id_acteur = ?');
        $req->execute(array($_SESSION['id_user'], $_GET['id_acteur']));
        $hasUserAlreadyVote = $req->fetch();
        $req->closeCursor();
        

        // Si il a pas déjà voté
        if(!$hasUserAlreadyVote)
        {

                $req2 = $bdd->prepare('INSERT INTO vote (id_user, id_acteur, vote)
                                    VALUES (:id_user, :id_acteur, :vote)');
                $req2->execute(array(
                    'id_user' => ($_SESSION['id_user']),
                    'id_acteur' => ($_GET['id_acteur']),
                    'vote' => ($_GET['vote'])
                ));

                $req2->closeCursor();
                
                header('Location: partenaire.php?id_acteur='. $_GET['id_acteur']);
                
        }

        else

        {


             header('Location: partenaire.php?id_acteur='. $_GET['id_acteur']);

        }
    }

}

else
{

    header('Location: index.php');

}

?>