<?php

include("account.php");


/*--------------------------- Vérification Utilisateur existe */

if (isset($_POST['username'])) {

    // Modifie Session username si formulaire envoyé
    $_SESSION['username'] = htmlspecialchars($_POST['username']);

    // cherche l'utilisateur
    $dataAccount = SearchUser($bdd, $_POST['username']);

    // Si l'utilisateur existe
    if (!empty($dataAccount)) {

        $userExist = true;

    } else {

        $userExist = false;
        $message = 1;
    }
} else {

    $userExist = false;
}


// Si l'username existe
if ($userExist) {

    // Vérification mot de passe (Hashé)
    $isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);

    // Si le mot de passe correspond
    if ($isPasswordCorrect) {
        // Connexion
        $_SESSION['nom'] = htmlspecialchars($dataAccount['nom']);
        $_SESSION['prenom'] = htmlspecialchars($dataAccount['prenom']);
        $_SESSION['id_user'] = $dataAccount['id_user'];

        header('Location: accueil.php');
    } else {
        $message = 3;
    }
}

include("header.php");

/* ------------------------------------------------HTML index---------------------------------------- */

?>

<main class="inscription-connexion">
			<section class="form_container">
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
								value="<?php ValueInputUsername(); ?>"
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
								name="connexionSubmit"
								value="Connexion"
								onclick=" UnsetPreviousSession()"
							/>

							<span
								>Les champs indiqués par une <em>*</em> sont
								obligatoires</span
							>
						</p>
					</form>

					<a href="mp.php"> mot de passe oublié ? </a>

					<a href="inscription.php"> créer un compte </a>
				</fieldset>
			</section>
		</main>

<?php include("footer.php"); ?>