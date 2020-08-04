<?php

include("account.php");

if (!isset($_SESSION['nom']) && !isset($_SESSION['prenom']) && !isset($_SESSION['id_user'])) {

    if (isset($_POST['dataSubmit'])) {

        // Cherche l'utilisateur dans la BDD (voir account)
        $dataAccount = searchUser($bdd, $_POST['username']);

        // Si l'user existe
        if ($dataAccount) {

            // Verification que tous les champs ne sont pas vides
            if (!empty($_POST['username']) && !empty($_POST['password']))
            {

                // Vérification mot de passe (Hashé)
                $isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);

                // Si le mot de passe correspond
                if ($isPasswordCorrect) {
                    // Connexion
                    $_SESSION['nom'] = htmlspecialchars($dataAccount['nom']);
                    $_SESSION['prenom'] = htmlspecialchars($dataAccount['prenom']);
                    $_SESSION['id_user'] = $dataAccount['id_user'];
                    $_SESSION['username'] = htmlspecialchars($dataAccount['username']);

                    header('Location: ../accueil.php');
                } else {
                    $message = 3;
                }
            } else {

                $message = 2;
            }
        } else {

            $message = 1;        
        }
    }

    include("header.php");

    /* ------------------------------------------------HTML index---------------------------------------- */

    ?>

    <main class="inscription-connexion">
                <div class="form_container">
                    <fieldset>
                        <legend>Se connecter :</legend>

                        <span class="message">
                            <?php messageError($message); ?>
                        </span>

                        <form method="post" action="index.php">
                            <p>
                                <label for="pseudo">Identifiant : </label>
                                <input
                                    type="text"
                                    id="pseudo"
                                    name="username"
                                    required
                                    value="<?php /* valueInputUsername(); */
                                    defaultInputValue('username', '')
                                    
                                    ?>"
                                />

                                <label for="mp">Mot de passe : </label>
                                <input
                                    type="password"
                                    id="mp"
                                    name="password"
                                    required
                                />

                                <input
                                    class="button-envoyer"
                                    type="submit"
                                    name="dataSubmit"
                                    value="Connexion"
                                    onclick=" unsetPreviousSession()"
                                />

                                <span
                                    >Les champs indiqués par une <em>*</em> sont
                                    obligatoires</span
                                >
                            </p>
                        </form>

                        <a href="../mp.php"> mot de passe oublié ? </a>

                        <a href="../inscription.php"> créer un compte </a>
                    </fieldset>
                </div>
            </main>

    <?php include("footer.php"); 
} else {

    header('Location: ../accueil.php');
}
?>