<?php

include("account.php");


// FONCTION pour effacer les valeurs de session 
function UnsetPreviousSession()
{
    unset($_SESSION['username']);
}

function deleteSession()
{
  $_SESSION = array();
  session_destroy();
}



/*--------------------------- Verification User */
if (isset($_POST['username']) && isset($_POST['password']))
{

  $_SESSION['username'] = $_POST['username'];

  // Cherche L'utilisateur dans la BDD (voir account.php)
  $dataAccount = SearchUser($bdd, $_POST['username']);

  // Si l'username existe
  if ($dataAccount)
  {  

    // Verification mot de passe (Hashé)
    $isPasswordCorrect = password_verify($_POST['password'], $dataAccount['password']);

    //si le mot de passe correspond
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

  }

  else
  {
      $erreur = "Cet identifiant n'existe pas";
  }

}


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
              if  (isset($erreur))
              {
                echo $erreur;
              }
            ?>
            
          </span>

          <form method="post"action="index.php">
            <p>

                <label for="pseudo">Identifiant : </label>
                <input type="text" id="pseudo" name="username" required 
                  <?php

                  if (isset($_SESSION['username']) && !isset($connexionSubmit))
                  {
                    echo 'value ="' . htmlspecialchars($_SESSION['username']).'"';
                  }

                  elseif (isset($connexionSubmit))
                  {
                    echo 'value ="' . htmlspecialchars($_POST['username']) .'"';
                  }

                  ?>
                >

                <label for="mp">Mot de passe : </label>
                <input type="password" id="mp" name="password"required>

                <input class="button-envoyer" type="submit" name ='connexionSubmit' value="Connexion" onclick ="UnsetPreviousSession()">

                <span>Les champs indiqués par une <em>*</em> sont obligatoires</span>

            </p>
          </form>

          <a href="mp.php"> mot de passe oublié ?</a>

          <a href="inscription.php">créer un compte</a>

        </fieldset>
      </section>
    </main>
    
<?php include("footer.php"); ?>