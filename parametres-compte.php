<?php

include("account.php");



// -------------------------entre sur la page Modification Profil
if (isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])) {

    // Cherche L'utilisateur dans la BDD (voir account.php)
    $dataAccountOld = SearchUser($bdd, $_SESSION['username']);

    // Si on change ses infos
    if (isset($_POST['dataSubmit'])) {

        //on cherche à savoir si l'username existe déjà
        $dataAccount = SearchUser($bdd, $_POST['username']);

        // Si l'username n'existe pas
        if (!$dataAccount or $dataAccountOld['username'] == $_POST['username']) {

            // Verification tous les champs ont été remplis
            if (
                !empty($_POST['nom']) && !empty($_POST['prenom'])
                && !empty($_POST['question']) && !empty($_POST['reponse'])
            ) {

                // et que le mot de passe est correct
                $isPasswordCorrect = password_verify($_POST['password'], $dataAccountOld['password']);

                if ($isPasswordCorrect && !empty($_POST['password'])) {

                    // Change les infos de la BDD
                    $req_update_infos_user = $bdd->prepare('UPDATE account SET 
                                                nom = :nom, 
                                                prenom = :prenom,
                                                question = :question,
                                                reponse = :reponse
                                                WHERE id_user = :id_user
                                                ');
                    $req_update_infos_user->execute(array(
                        'nom' => ($_POST['nom']),
                        'prenom' => ($_POST['prenom']),
                        'question' => ($_POST['question']),
                        'reponse' => ($_POST['reponse']),
                        'id_user' => $dataAccount['id_user']
                    ));
                    $req_update_infos_user->closeCursor();

                    // Récupère les nouvelles valeurs de SESSION (fonction voir account.php)
                    $dataAccountNew = SearchUser($bdd, $_SESSION['username']);

                    $_SESSION['nom'] = htmlspecialchars($dataAccountNew['nom']);
                    $_SESSION['prenom'] = htmlspecialchars($dataAccountNew['prenom']);

                    $message = 5;
                } else {

                    $message = 3;
                }
            } else {

                $message = 2;
            }
        } else {

            $message = 10;
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
            <span class="message-erreur">
                <?php messageError($message); ?>
            </span>
            <!-------- Formulaire -->
            <form method="post" action="parametres-compte.php">
                <p>
                    <label for="pseudo">Identifiant : </label>
                    <input 
                        type="text"
                        id="pseudo"
                        name="username"
                        size="20" 
                        value="<?php defaultInputValue('username', $dataAccountOld['username']);?>"
                    />

                    <label for="nom">Nom : </label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        size="30"
                        value="<?php defaultInputValue('nom', $dataAccountOld['nom']);?>"
                    />


                    <label for="prenom">Prénom : </label>
                    <input 
                        type="text"
                        id="prenom"
                        name="prenom"
                        size="30"
                        value="<?php defaultInputValue('prenom', $dataAccountOld['prenom']);?>"
                    />


                    <label for="question">
                        Votre question secrète : 
                    </label>
                    <input
                        type="textarea"
                        id="question"
                        name="question"
                        value="<?php defaultInputValue('question', $dataAccountOld['question']);?>"
                    />


                    <label for="reponse">
                        La réponse à votre question : 
                    </label>
                    <input
                        type="textarea" 
                        id="reponse" 
                        name="reponse"
                        value="<?php defaultInputValue('reponse', $dataAccountOld['reponse']);?>"
                    />

                    <label for="mp">Entrez votre mot de passe: </label>
                    <input
                        type="password"
                        id="mp"
                        name="password"
                        size="20"
                    />

                    <input
                        class="button-envoyer"
                        type="submit"
                        name="dataSubmit"
                        value="Envoyer"
                        onclick="UnsetPreviousSession()"
                    />

                    <span>
                        <em>*</em> Tous les champs doivent être remplis
                    </span>

                </p>
            </form>

            <a href="mp.php"> changer son mot de passe </a>

            <a href="accueil.php"> Retour à l'accueil </a>
        </fieldset>
    </section>
</main>

<?php
include("footer.php");

} else {
    header('Location: index.php');
}
?>