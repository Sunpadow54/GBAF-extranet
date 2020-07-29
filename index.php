<?php

include("account.php");



/* // Modifie Session username si formulaire envoyé
if (isset($_POST['username']))
{
  // Modifie Session username si formulaire envoyé
  $_SESSION['username'] = htmlspecialchars($_POST['username']);
  
} */


/*--------------------------- Vérification Utilisateur existe */

if (isset($_POST['username']) )
{

	// Modifie Session username si formulaire envoyé
	$_SESSION['username'] = htmlspecialchars($_POST['username']);

	// cherche l'utilisateur
	$dataAccount = SearchUser($bdd, $_POST['username']);

	// Si l'utilisateur existe
	if (!empty($dataAccount)) {

		$userExist = true;
		$erreur = "Cet identifiant existe"; 

	} else {

		$userExist = false;
		$erreur = "Cet identifiant n'existe pas";

  	}

} else {
  
  	$userExist = false;

}


// Si l'username existe
if ($userExist)	{  

	// Vérification mot de passe (Hashé)
	$isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);

	// Si le mot de passe correspond
	if ($isPasswordCorrect)
	{
		// Connexion
		$_SESSION['nom'] = htmlspecialchars($dataAccount['nom']);
		$_SESSION['prenom'] = htmlspecialchars($dataAccount['prenom']);
		$_SESSION['id_user'] = $dataAccount['id_user'];

		header('Location: accueil.php');
	}

	else
	{
		$erreur = "Ce n'est pas le bon mot de passe";
	}

} /* else {

	echo 'nope' ;

} */

/*   else
{
	$erreur = "Cet identifiant n'existe pas";
} */



include("header.php");

?>


<!-- ---------------------------Formulaire Connexion -->      

    <main class="inscription-connexion">
      <section class="form_container">
        <fieldset>

          <legend> Se connecter : </legend>

          <span class="message"> 
            
            <?php
				if (!isset($_POST['connexionSubmit']))
				{
					// message si la personne viens de changer de mp
					if (isset($_SESSION['messagePWchanged']))
					{
					echo $_SESSION['messagePWchanged'];
					}
					// message si la personne viens de s'inscrire
					if  (isset($_SESSION['messageWelcome']))
					{
					echo $_SESSION['messageWelcome'];
					}
				}

				// message si erreur de connexion
				if  (isset($erreur))	{

					echo $erreur;
					
				}
            ?>
            
          </span>

          <form method="post"action="index.php">
            <p>

                <label for="pseudo">Identifiant : </label>
                <input type="text" id="pseudo" name="username" required value ="<?php ValueInputUsername(); ?>" >

                <label for="mp">Mot de passe : </label>
                <input type="password" id="mp" name="password"required>

                <input class="button-envoyer" type="submit" name ='connexionSubmit' value="Connexion" onclick =" UnsetPreviousSession()">

                <span>Les champs indiqués par une <em>*</em> sont obligatoires</span>

            </p>
          </form>

          <a href="mp.php"> mot de passe oublié ?</a>

          <a href="inscription.php">créer un compte</a>

        </fieldset>
      </section>
    </main>
    
<?php include("footer.php"); ?>