<?php

include("account.php");


// FONCTION pour effacer les valeurs de session
function UnsetPreviousSession()
{
    unset($_SESSION['name']);
    unset($_SESSION['prenom']);
}


// -------------------------entre sur la page Modification Profil
if  (isset($_SESSION['id_user']) && !empty($_SESSION['id_user']))   {

    // Cherche L'utilisateur dans la BDD (voir account.php)
    $dataAccount = SearchUser($bdd, $_SESSION['username']);

    // Si on change ses infos
    if  (isset($_POST['dataSubmit']))   {

        //on cherche à savoir si l'username existe déjà
        $req2 = $bdd->prepare('SELECT username FROM account WHERE username = ?');
        $req2->execute(array($_POST['username']));
        $isUsernameExist = $req2->fetch();

        // Si l'username n'existe pas
        if  (!$isUsernameExist OR $dataAccount['username'] == $_POST['username'])   {
        

            // Verification tous les champs ont été remplis
            if  (!empty($_POST['nom']) && !empty($_POST['prenom'])
                && !empty($_POST['question']) && !empty($_POST['reponse']))   {

                    // et que le mot de passe est correct
                    $isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);

                    if  ($isPasswordCorrect && !empty($_POST['password']))  {

                        // Change les infos de la BDD
                        $req3 = $bdd->prepare ('UPDATE account SET 
                                                nom = :nom, 
                                                prenom = :prenom,
                                                question = :question,
                                                reponse = :reponse
                                                WHERE id_user = :id_user
                                                ');
                        $req3->execute (array(
                            'nom' => ($_POST['nom']),
                            'prenom' => ($_POST['prenom']),
                            'question' => ($_POST['question']),
                            'reponse' => ($_POST['reponse']),
                            'id_user' => $dataAccount['id_user']
                        ));
                        $req3->closeCursor();

                        // Récupère les nouvelles valeurs de SESSION (fonction voir account.php)
                        $dataAccountNew = SearchUser($bdd, $_SESSION['username']);

                        $_SESSION['nom'] = htmlspecialchars($dataAccountNew['nom']);
                        $_SESSION['prenom'] = htmlspecialchars($dataAccountNew['prenom']);

                        $message = 'Vos changements ont bien été pris en compte';

                    }

                } else {

                    $message =" Veuillez remplir tous les champs";

                }

        } else {

            $message = " Ce nom d'utilisateur existe déjà ";

        }
  
    }


/* ------------------------------------------------ HTML FORMULAIRE INSCRIPTION ---------------------------------------- */


include("header.php"); 

?>
 

    <main class="inscription-connexion">
        <section class="form_container">
            <fieldset>

                <legend>Modifier son Profil</legend>
                        
                <!-- message erreur -->
                <span class="message-erreur"> <?php MessageError($message); ?> </span>
                
                <!-------- Formulaire avec 'value' préenregistrées -->  
                <form method="post" action="profil">
                    <p>

                        <label for="pseudo">Identifiant : </label>
                        <input type="text" id="pseudo" name="username" size="20" 

                            <?php
                            // VALUE USERNAME si il n'y a pas eu de submit
                            if (isset($dataAccount['username']) && !isset($_POST['dataSubmit']))
                            {
                                echo 'value = "' .htmlspecialchars($dataAccount['username']). '"' ;
                            }
                            // Sinon affiche le nouveau username
                            elseif (isset($_POST['dataSubmit']))
                            {
                                echo 'value="' .htmlspecialchars($_POST['username']). '"' ;
                            }
                            ?>

                        >

                        <label for="nom">Nom : </label>
                        <input type="text" id="nom" name="nom" size="30"

                            <?php
                            // VALUE NOM si il n'y a pas eu de submit
                            if (isset($dataAccount['nom']) && !isset($_POST['dataSubmit']))
                            {
                                echo 'value = "' .htmlspecialchars($dataAccount['nom']). '"' ;
                            }
                            // Sinon affiche le nouveau nom
                            elseif (isset($_POST['dataSubmit']))
                            {
                                echo 'value="' .htmlspecialchars($_POST['nom']). '"' ;
                            }
                            ?>

                        >


                        <label for="prenom">Prénom : </label>
                        <input type="text" id="prenom" name="prenom" size="30"

                            <?php
                            // VALUE PRENOM si il n'y a pas eu de submit
                            if (isset($dataAccount['prenom']) && !isset($_POST['dataSubmit']))
                            {
                                echo 'value = "' .htmlspecialchars($dataAccount['prenom']). '"' ;
                            }
                            // Sinon on affiche le nouveau prenom
                            elseif (isset($_POST['dataSubmit']))
                            {
                                echo 'value="' .htmlspecialchars($_POST['prenom']). '"' ;
                            }
                            ?>

                        >
                    

                        <label for="question">Votre question secrète : </label>
                        <input type="textarea" id="question" name="question"

                            <?php
                            // VALUE QUESTION si il n'y a pas eu de submit
                            if (isset($dataAccount['question']) && !isset($_POST['dataSubmit']))
                            {
                                echo 'value = "' .htmlspecialchars($dataAccount['question']). '"' ;
                            }
                            // Sinon on affiche la nouvelle question
                            elseif (isset($_POST['dataSubmit']))
                            {
                                echo 'value="' .htmlspecialchars($_POST['question']). '"' ;
                            }
                            ?>

                        >


                        <label for="reponse">La réponse à votre question : </label>
                        <input type="textarea" id="reponse" name="reponse"

                            <?php
                            // VALUE REPONSE si il n'y a pas eu de submit
                            if (isset($dataAccount['reponse']) && !isset($_POST['dataSubmit']))
                            {
                                echo 'value = "' .htmlspecialchars($dataAccount['reponse']). '"' ;
                            }
                            // Sinon on affiche la nouvelle reponse
                            elseif (isset($_POST['dataSubmit']))
                            {
                                echo 'value="' .htmlspecialchars($_POST['reponse']). '"' ;
                            }
                            ?>

                        >
                        
                        <label for="mp">Entrez votre mot de passe: </label>
                        <input type="password" id="mp" name="password" size="20">

                        <input class="button-envoyer" type="submit" name="dataSubmit" value="Envoyer" onclick="UnsetPreviousSession()" >

                        <span><em>*</em> Tous les champs doivent être remplis</span>

                    </p>
                </form>

                <a href="accueil.php">Retour à l'accueil</a>

            </fieldset>
        </section>
    </main>

<?php
 include("footer.php");
}

// Si pas de Session , pas accès à Profil
else
{
    header('Location: index.php');
}

?>