<?php


include("account.php");


// Les Formulaires 
$formDefault = '<label for="pseudo">Identifiant : </label>
      <input type="text" id="pseudo" name="username" size="20">';

$formQuestion ='<input type="textarea" id="reponse" name="reponse">';

$fomPasswordChange = '<label for="mp">Nouveau mot de passe : </label>
      <input type="password" id="mp" name="password" size="20">';

$formType = $formDefault;



/*----------------------- Vérification Utilisateur existe */

if (isset($_POST['username']) )
{

    // Modifie Session username si formulaire envoyé
    $_SESSION['username'] = htmlspecialchars($_POST['username']);

    // cherche l'utilisateur
    $dataAccount = SearchUser($bdd, $_POST['username']);

    // Si l'utilisateur existe
    if (!empty($dataAccount)){

        $userExist = true;
        $usernameValid = $_POST['username'];

    } else {
        
        $userExist = false;
        $message = 1;

    }
} else {

  $userExist = false;

}


/*-----------------------  Si l'utilisateur existe, Affiche la question et son formulaire */

if  ($userExist)    { 
    
    $formType = $formQuestion;
    // On donne la question
    $questionUser = '<label for="reponse">' . $dataAccount['question'] . ' </label>';
    $message = 6;
    // récupère son username pour les autres formulaires
    $_SESSION['username'] = $dataAccount['username'];

} /* else {

    $message = 'cet identifiant n\'existe pas';

} */
    

/*----------------------- Si on répond à la question secrète */

if  (isset($_POST['reponse'])){
    
    $formType = $formQuestion;
    $dataAccount = SearchUser($bdd, $_SESSION['username']);
    $questionUser = $dataAccount['question'];
    $message = 6;

    // Si la réponse correspond
    if  ($_POST['reponse'] == $dataAccount['reponse'])  {

        unset($questionUser);
        // On affiche le formulaire de changement de mot de passe
        $formType = $fomPasswordChange;
        $message = 8;

    } else {

        $questionUser = $dataAccount['question'];
        $message = 7;

    }
}


/*----------------------- Si on modifie son mot de passe */

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

        $message = 9;
        $_SESSION['message'] = $message;

        header('Location: index.php');

    } else  {

        // Si le mot de passe n'est pas conforme
        $message = 4;
    
    }

}


/* ------------------------------------------------HTML---------------------------------------- */
 
include("header.php");

?>


<!-- ---------------------------HTML FORMULAIRE CHANGEMENT DE MP -->

<main class="inscription-connexion">
    <section class="form_container">
        <fieldset>

            <legend> Mot de passe oublié ? </legend>

            <!-- message erreur -->
            <span class="message-erreur"> <?php messageError($message); ?> </span>

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

            <a href="index.php"> Connexion </a>

            <a href="inscription.php"> créer un compte </a>

        </fieldset>
    </section>
</main>


<?php

include("footer.php");

?>
