<?php

include("account.php");



// Formulaires 
$formDefault = '<label for="pseudo">Identifiant : </label>
      <input type="text" id="pseudo" name="username" size="20">';

$formQuestion ='<input type="textarea" id="reponse" name="reponse">';

$fomPasswordChange = '<label for="mp">Nouveau mot de passe : </label>
      <input type="password" id="mp" name="password" size="20">';

$formType = $formDefault;


// Si on envoie son username on récupère session username
if (isset($_POST['username']))  {

    $_SESSION['username'] = $_POST['username'];

}

// Si on viens d'arriver sur la page, on efface toutes les Sessions existantes (securité)
elseif (!isset($_POST['dataPosted']))   {

    $_SESSION = array();
    session_destroy();

}


/*  -------------------------------- Si Session username existe -------------------------------- */
if  (isset($_SESSION['username']))   {

    // Cherche L'utilisateur dans la BDD (voir account.php)
    $dataAccount = SearchUser($bdd, $_SESSION['username']);

    // ------------------------------ Si l'utilisateur existe
    if  ($dataAccount)    { 
        
        // On donne la question
        $formType = $formQuestion;
        $questionUser = '<label for="reponse">' . $dataAccount['question'] . ' </label>';
        $erreur = 'répondez à votre question secrète : ';
        
    } else {

        $erreur = 'cet identifiant n\'existe pas';

    }


    // ------------------------------ Si on envoie une réponse
    if  (isset($_POST['reponse'])){
         
        $questionUser = $dataAccount['question'];
        $formType = $formQuestion;
        
        $erreur = 'répondez à votre question secrète : ';

        // Si la réponse correspond
        if  ($_POST['reponse'] == $dataAccount['reponse'])  {

            unset($questionUser);

            // On affiche le formulaire de changement de mot de passe
            $formType = $fomPasswordChange;
            $erreur = 'Vous pouvez changer votre mot de passe : ';

        } else {

            $erreur = 'Ce n\'est pas la réponse attendue';

        }
    }

    // ------------------------------ Si on envoie un nouveau mot de passe
    if  (isset($_POST['password'])) {
        
        unset($questionUser);

        $formType = $fomPasswordChange;

        // Si le nouveau mot de passe est conforme
        if  (preg_match( "#(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z.-_]{4,}#", $_POST['password'] ))    {

            // On Hash le mot de passe
            $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // On remplace le mp dans la base de données
            $req2 = $bdd->prepare('UPDATE account SET
                                    password = :password
                                    WHERE username = :username
                                ');
            $req2->execute  (array(
                'password' => $passwordHashed,
                'username' => $_SESSION['username']
            ));

            $req2->closeCursor();

            $_SESSION['messagePWchanged'] = 'Votre mot de passe à bien été changé . <br> Vous pouvez vous connecter';

            header('Location: index.php');

        } else  {

            // Si le mot de passe n'est pas conforme
            $erreur ="le mot de passe doit contenir au moins 4 caractères, dont une minuscule, une majuscule et un chiffre";
        
        }

    }

}



/* ------------------------------------------------HTML---------------------------------------- */

include("header.php");

?>


<!-- ---------------------------HTML FORMULAIRE CHANGEMENT DE MP -->      
<main class="inscription-connexion">
    <section class="form_container">
        <fieldset>

            <legend>Mot de passe oublié ?</legend>

            <!-- message erreur span-->
            <?php 

            if (isset($erreur)) {

            echo '<span class="message-erreur">' . $erreur .' </span>';

            }

            ?>


            <form method="post"action="mp.php">
                <p>

                    <?php

                    // affiche la question secrète
                    if  (isset($questionUser))  {

                        echo $questionUser;

                    }

                    // affiche le formulaire adéquat
                    echo $formType;

                    ?>

                    <input class="button-envoyer" type="submit" name="dataPosted" value="Envoyer">

                </p>
            </form>

            <a href="index.php">Connexion</a>

        </fieldset>
    </section>
</main>


<?php

include("footer.php");

?>
