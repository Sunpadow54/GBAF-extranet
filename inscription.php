<?php

require_once('core/account.php');

if (!isset($_SESSION['nom']) && !isset($_SESSION['prenom']) && !isset($_SESSION['id_user'])) {

    //------------VERIFICATION & AJOUT NOUVEL UTILISATEUR

    // Verifie si les variables ont été crées
    if (isset($_POST['dataSubmit'])) {

        // Cherche si l'utilisateur dans la BDD (voir account)
        $dataAccount = searchUser($bdd, $_POST['username']);

        // Si l'username n'existe pas
        if (!$dataAccount) {

            // Verification que tous les champs ne sont pas vide
            if (
                !empty($_POST['nom']) && !empty($_POST['prenom'])
                && !empty($_POST['username']) && !empty($_POST['password'])
                && !empty($_POST['question']) && !empty($_POST['reponse'])
            ) {

                // Verification que le mot de passe contient minimum 1 lettre 1 maj et 1 chiffre
                if (preg_match($mpValid, $_POST['password'])) {

                    // Hashage du mot de passe
                    $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $reponseHashed = password_hash($_POST['reponse'], PASSWORD_DEFAULT);
                    // Insère le nouvel Utilisateur dans la BDD
                    $req_add_user = $bdd->prepare('INSERT INTO account (nom, prenom, username, password, question, reponse) 
                                            VALUES (:nom, :prenom, :username, :password, :question, :reponse)');
                    $req_add_user->execute(array(
                        'nom' => ($_POST['nom']),
                        'prenom' => ($_POST['prenom']),
                        'username' => ($_POST['username']),
                        'password' => $passwordHashed,
                        'question' => ($_POST['question']),
                        'reponse' => $reponseHashed
                    ));

                    $req_add_user->closeCursor();

                    $message = 11;

                    $_SESSION['username'] = htmlspecialchars($_POST['username']);
                    $_SESSION['message'] = $message;

                    header('Location: /index.php');
                } else {

                    $message = 4;
                }
            } else {

                $message = 2;
            }
        } else {
            // Username existe déjà
            $message = 10;
        }
    }


    require_once('layout/header.php');

    /* ------------------------------------------------HTML page d'inscription---------------------------------------- */

    ?>


    <main class="inscription-connexion">
        <div class="form_container">
            <fieldset>
                <legend>Créer un compte :</legend>

                <!-- message erreur -->
                <span class="message-erreur">
                    <?php messageError($message); ?>
                </span>

                <!-- Formulaire avec 'value' préenregistrées -->
                <form method="post" action="inscription.php">
                    <p>
                        <label for="pseudo">Identifiant : </label>
                        <input
                            type="text"
                            id="pseudo"
                            name="username"
                            size="20"
                            value="<?php defaultInputValue('username', '');?>"
                        />

                        <label for="mp">Mot de passe : </label>
                        <input
                            type="password"
                            id="mp"
                            name="password"
                            size="20"
                        />

                        <label for="nom">Nom : </label>
                        <input 
                            type="text" 
                            id="nom" 
                            name="nom" 
                            size="30"
                            value="<?php defaultInputValue('nom', '');?>"
                        />

                        <label for="prenom">Prénom : </label>
                        <input 
                            type="text" 
                            id="prenom" 
                            name="prenom" 
                            size="30"
                            value="<?php defaultInputValue('prenom', '');?>"
                        />

                        <label for="question">
                            Votre question secrète : 
                        </label>
                        <input 
                            type="text" 
                            id="question" 
                            name="question"
                            value="<?php defaultInputValue('question', '');?>"
                        />

                        <label for="reponse">
                            La réponse à votre question : 
                        </label>
                        <input 
                            type="text" 
                            id="reponse" 
                            name="reponse"
                            value="<?php defaultInputValue('reponse', '');?>"
                        />

                        <input
                            class="button-envoyer"
                            type="submit"
                            name="dataSubmit"
                            value="Envoyer"
                        />

                        <span>
                            Les champs indiqués par une * sont obligatoires
                        </span>

                    </p>
                </form>

                <a href="../index.php"> se connecter </a>

                <a href="../mp.php"> mot de passe oublié ? </a>
            </fieldset>
        </div>
    </main>

    <?php 

    require_once('layout/footer.php');
} else {

    header('Location: /espace-membre/accueil.php');
}
?>