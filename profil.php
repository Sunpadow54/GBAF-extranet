<?php

session_start();

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

// --------Modification Profil
if (isset($_SESSION['id_user']) && !empty($_SESSION['id_user']))
{
    // Cherche l'utilisateur dans la BDD
    $req = $bdd->prepare('SELECT * FROM account WHERE id_user = ?');
    $req->execute(array($_SESSION['id_user']));
    $dataAccount = $req->fetch();
    $req->closeCursor();

    // Verification si changements fait -> variables créées
    if(isset($_POST['dataSubmit']))
    {
        //Verification tous les champs ont été remplis
        if  (!empty($_POST['nom']) && !empty($_POST['prenom'])
            && !empty($_POST['question']) && !empty($_POST['reponse']))
        {

            // et que le mot de passe est correct
            $isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);
            if  ($isPasswordCorrect && !empty($_POST['password']))
            {
                // Change les infos de la BDD
                $req2 = $bdd->prepare ('UPDATE account SET 
                                        nom = :nom, 
                                        prenom = :prenom,
                                        question = :question,
                                        reponse = :reponse
                                        WHERE id_user = :id_user
                                        ');
                $req2->execute (array(
                    'nom' => htmlspecialchars($_POST['nom']),
                    'prenom' => htmlspecialchars($_POST['prenom']),
                    'question' => htmlspecialchars($_POST['question']),
                    'reponse' => htmlspecialchars($_POST['reponse']),
                    'id_user' => $dataAccount['id_user']
                ));
                $req2->closeCursor();

                $message = 'Vos changements ont bien été pris en compte <br>' . $_SESSION['prenom'];
            
            }
            else
            {
                $erreur ="Le mot de passe est incorrect ou manquant";
            }
        }
        else
        {
            $erreur =" Veuillez remplir tous les champs";
        }
    }

?>
 
<!-------------- HTML FORMULAIRE Modification Profil -->

<!DOCTYPE html>

<html>

  <head>
  
    <meta charset="utf-8" />

    <title> GBAF extranet- Profil</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" media="all and (min-device-width : 320px) and (max-device-width : 480px)" href="style-mobile.css">
    <link rel="stylesheet" media="all and (max-device-width: 1280px ) and (min-device-width: 481px)" href="style-tablette.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;900&display=swap" rel="stylesheet">  
    
  </head>
    
  <body>

    <main class="inscription-connexion">

      <fieldset>

        <legend>Modifier son Profil</legend>
        
        <img src="../images/GBAF.png" alt="Logo GBAF">
                
        <?php
            // message d'erreur
            if (isset($erreur))
            {
                echo '<span class="message-erreur">' . $erreur .' </span>'; 
            }
            elseif (isset($message))
            {
                echo '<span class="message-erreur">' . $message .' </span>'; 
            }
        ?>

        <?php
            // affichage username qui ne peut pas être changé
            if (isset($dataAccount['username']))
            {
                 echo '<h2>' .htmlspecialchars($dataAccount['username']). '</h2>' ;
            }
        ?>
        
<!-------- Formulaire avec 'value' préenregistrées -->  
        <form method="post" action="profil">

        <p>

            <label for="nom">Nom : </label>
            <input type="text" id="nom" name="nom" size="30"

            <?php
            // VALUE nom
            // Recupère et Affiche le nom si il n'y a pas eu de submit
            if (isset($dataAccount['nom']) && !isset($_POST['dataSubmit']))
            {
                echo 'value = "' .htmlspecialchars($dataAccount['nom']). '"' ;
            }
            // Si il y a eu Submit alors on affiche le nouveau nom
            elseif (isset($_POST['dataSubmit']))
            {
                echo 'value="' .htmlspecialchars($_POST['nom']). '"' ;
            }
            ?>

            >


            <label for="prenom">Prénom : </label>
            <input type="text" id="prenom" name="prenom" size="30"

            <?php
            // VALUE prenom
            // Recupère et Affiche le prenom si il n'y a pas eu de submit
            if (isset($dataAccount['prenom']) && !isset($_POST['dataSubmit']))
            {
                echo 'value = "' .htmlspecialchars($dataAccount['prenom']). '"' ;
            }
            // Si il y a eu Submit alors on affiche le nouveau prenom
            elseif (isset($_POST['dataSubmit']))
            {
                echo 'value="' .htmlspecialchars($_POST['prenom']). '"' ;
            }
            ?>

            >
        

            <label for="question">Votre question secrète : </label>
            <input type="textarea" id="question" name="question"

            <?php
            // VALUE question
            // Recupère et Affiche la question si il n'y a pas eu de submit
            if (isset($dataAccount['question']) && !isset($_POST['dataSubmit']))
            {
                echo 'value = "' .htmlspecialchars($dataAccount['question']). '"' ;
            }
            // Si il y a eu Submit alors on affiche la nouvelle question
            elseif (isset($_POST['dataSubmit']))
            {
                echo 'value="' .htmlspecialchars($_POST['question']). '"' ;
            }
            ?>

            >


            <label for="reponse">La réponse à votre question : </label>
            <input type="textarea" id="reponse" name="reponse"

            <?php
            // VALUE reponse
            // Recupère et Affiche la reponse si il n'y a pas eu de submit
            if (isset($dataAccount['reponse']) && !isset($_POST['dataSubmit']))
            {
                echo 'value = "' .htmlspecialchars($dataAccount['reponse']). '"' ;
            }
            // Si il y a eu Submit alors on affiche la nouvelle reponse
            elseif (isset($_POST['dataSubmit']))
            {
                echo 'value="' .htmlspecialchars($_POST['reponse']). '"' ;
            }
            ?>

            >
            
            <label for="mp">Entrez votre mot de passe: </label>
            <input type="password" id="mp" name="password" size="20">

            <input class="button-envoyer" type="submit" name="dataSubmit"value="Envoyer">


        </p>

        </form>

        <a href="accueil.php">Retour à l'accueil</a>

      <fieldset>

    </main>

  </body>
  
</html>

    <?php
}

// -------Modification Mot de Passe

?>