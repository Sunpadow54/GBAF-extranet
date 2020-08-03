<?php


include("account.php");


// Les Formulaires 
$formDefault = '<label for="pseudo">Identifiant : </label>
      <input type="text" id="pseudo" name="username" size="20">';

$formQuestion = '<input type="textarea" id="reponse" name="reponse">';

$fomPasswordChange = '<label for="mp">Nouveau mot de passe : </label>
      <input type="password" id="mp" name="password" size="20">';

$formType = $formDefault;



/*-----------------------  Si l'utilisateur existe, Affiche la question et son formulaire */

if (isset($_POST['username'])) {

    // Cherche si l'utilisateur dans la BDD (voir account.php)
    $dataAccount = searchUser($bdd, $_POST['username']);

    if ($dataAccount) {

        // récupère son username pour les autres formulaires
        $_SESSION['username'] = $dataAccount['username'];
        // On donne la question
        $formType = $formQuestion;
        $questionUser = '<label for="reponse">' . $dataAccount['question'] . ' </label>';
        $message = 6;
    } else {

        $message = 1;
    }
} 

/*----------------------- Si on répond à la question secrète */

if (isset($_POST['reponse'])) {

    $formType = $formQuestion;
    $dataAccount = searchUser($bdd, $_SESSION['username']);
    $questionUser = $dataAccount['question'];
    $message = 6;
    $isGoodAnswer = password_verify($_POST['reponse'], $dataAccount['reponse']);

    // Si la réponse correspond
    if ($isGoodAnswer){

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

if (isset($_POST['password'])) {

    unset($questionUser);
    $formType = $fomPasswordChange;

    // Si le nouveau mot de passe est conforme
    if (preg_match($mpValid, $_POST['password'])) {

        // On Hash le mot de passe
        $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // On remplace le mp dans la base de données
        $req_update_password = $bdd->prepare('UPDATE account SET
                                password = :password
                                WHERE username = :username
                            ');
        $req_update_password->execute(array(
            'password' => $passwordHashed,
            'username' => $_SESSION['username']
        ));
        $req_update_password->closeCursor();

        $message = 9;
        $_SESSION['message'] = $message;

        header('Location: index.php');
    } else {

        // Si le mot de passe n'est pas conforme
        $message = 4;
    }
}


/* ------------------------------------------------HTML Changement de mp---------------------------------------- */

include("header.php");

?>

<main class="inscription-connexion">
    <section class="form_container">
        <fieldset>
            <legend> Changer son mot de passe </legend>

            <!-- message erreur -->
            <span class="message-erreur"> 
                <?php messageError($message); ?> 
            </span>

            <form method="post" action="mp.php">
                <p>
                    <?php
                    // affiche la question secrète
                    if (isset($questionUser)) {

                        echo $questionUser;
                    }

                    // affiche le formulaire adéquat
                    echo $formType;
                    ?>

                    <input class="button-envoyer" 
                        type="submit" 
                        name="dataSubmit" 
                        value="Envoyer"
                    />
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