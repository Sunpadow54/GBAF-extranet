<?php

include("account.php");


//------------VERIFICATION & AJOUT NOUVEL UTILISATEUR


// Verifie si les variables ont été crées
if (isset($_POST['dataPosted']))    {

    // Cherche si l'utilisateur dans la BDD (voir account.php)
    $dataAccount = SearchUser($bdd, $_POST['username']);
    

    // Si l'username n'existe pas
    if (!$dataAccount)  {

        // Verification que tous les champs ne sont pas vide
        if  (!empty($_POST['nom']) && !empty($_POST['prenom']) 
            && !empty($_POST['username']) && !empty($_POST['password']) 
            && !empty($_POST['question']) && !empty($_POST['reponse']))  {


            // Verification que le mot de passe contient minimum 1 lettre 1 maj et 1 chiffre
            if (preg_match( "#(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z.-_]{4,}#", $_POST['password']))  {

                // Hashage du mot de passe
                $passwordHashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Insère le nouvel Utilisateur dans la BDD
                $req2 = $bdd->prepare ('INSERT INTO account (nom, prenom, username, password, question, reponse) 
                                        VALUES (:nom, :prenom, :username, :password, :question, :reponse)');
                $req2->execute(array(
                'nom' => ($_POST['nom']),
                'prenom' => ($_POST['prenom']),
                'username' => ($_POST['username']),
                'password' => $passwordHashed,
                'question' => ($_POST['question']),
                'reponse' => ($_POST['reponse'])
                ));

                $req2->closeCursor();

                $message = 11;

                $_SESSION['username'] = htmlspecialchars($_POST['username']);
                $_SESSION['message'] = $message;

                header('Location: index.php');

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


include("header.php");
?>


<!-------------- FORMULAIRE D'INSCRIPTION -->

<main class="inscription-connexion">
    <section class="form_container">
    <fieldset>

        <legend> Créer un compte : </legend>
                    
        <!-- message erreur -->
        <span class="message-erreur"> <?php messageError($message); ?> </span>

        <!-- Formulaire avec 'value' préenregistrées -->  
        <form method="post" action="inscription">
            <p>

            <label for="pseudo">Identifiant : </label>
            <input type="text" id="pseudo" name="username" size="20" 

                <?php
                if (isset($_POST['username']))
                {
                echo 'value = "' .htmlspecialchars($_POST['username']). '"' ;
                }
                ?>

            >


            <label for="mp">Mot de passe : </label>
            <input type="password" id="mp" name="password" size="20">


            <label for="nom">Nom : </label>
            <input type="text" id="nom" name="nom" size="30"

                <?php
                if (isset($_POST['nom']))
                {
                echo 'value = "' .htmlspecialchars($_POST['nom']). '"' ;
                }
                ?>

            >


            <label for="prenom">Prénom : </label>
            <input type="text" id="prenom" name="prenom" size="30"

            <?php
                if (isset($_POST['prenom']))
                {
                echo 'value = "' .htmlspecialchars($_POST['prenom']). '"' ;
                }
            ?>

            >
        

            <label for="question">Votre question secrète : </label>
            <input type="textarea" id="question" name="question"

                <?php
                if (isset($_POST['question']))
                {
                echo 'value = "' .htmlspecialchars($_POST['question']). '"' ;
                }
                ?>

            >


            <label for="reponse">La réponse à votre question : </label>
            <input type="textarea" id="reponse" name="reponse"

                <?php
                if (isset($_POST['reponse']))
                {
                echo 'value = "' .htmlspecialchars($_POST['reponse']). '"' ;
                }
                ?>

            >


            <input class="button-envoyer" type="submit" name="dataPosted" value="Envoyer">
            
            <span>Les champs indiqués par une * sont obligatoires</span>

            </p>
        </form>

        <a href="index.php"> se connecter </a>

        <a href="mp.php"> mot de passe oublié ? </a>

        </fieldset>
    </section>
</main>
    
<?php include("footer.php"); ?>