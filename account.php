<?php

session_start();

/* Base de donnée connexion :*/
try {
    $bdd = new PDO(
        'mysql:host=localhost;
        dbname=gbaf-extranet;
        charset=utf8',
        'root',
        '',
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (Exception $e) {
    die(' Erreur : ' . $e->getMessage());
}



// Fonction cherche l'utilisateur
function SearchUser($bdd, $userName)
{

    $req = $bdd->prepare('SELECT * FROM account WHERE username = ?');
    $req->bindValue(1, $userName);
    $req->execute();
    $dataAccount = $req->fetch();
    $req->closeCursor();

    return $dataAccount;
}



// message / erreur
$message = '';

// message si la personne viens de changer de mp OU viens de s'inscrire
if (!isset($_POST['connexionSubmit'])) {

    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
    }

    unset($_SESSION['message']);
}

//  Fonction message / erreur
function messageError($message)
{

    switch ($message) {

        case 1:
            echo "Cet identifiant n'existe pas";
            break;

        case 2:
            echo "Veuillez remplir tous les champs";
            break;

        case 3:
            echo "Ce n'est pas le bon mot de passe";
            break;

        case 4:
            echo "Le mot de passe doit contenir au moins 4 caractères, dont une minuscule, une majuscule et un chiffre";
            break;

            // Paramètre-compte modification
        case 5:
            echo "Vos changements ont bien été pris en compte";
            break;

            // Modification MP
        case 6:
            echo "répondez à votre question secrète : ";
            break;

        case 7:
            echo "Ce n'est pas la réponse attendue";
            break;

        case 8:
            echo "Vous pouvez changer votre mot de passe : ";
            break;

        case 9:
            echo "Votre mot de passe à bien été changé . <br> Vous pouvez vous connecter";
            break;

            // inscription
        case 10:
            echo "Cet identifiant existe déjà ";
            break;

            // après inscription
        case 11:
            echo "Bienvenue ! Vous pouvez vous connecter";
            break;
    }
}



// Fonction garde en mémoire la value postée de l'username
function ValueInputUsername()
{

    if (isset($_SESSION['username']) && !isset($connexionSubmit)) {

        echo htmlspecialchars($_SESSION['username']);
    } elseif (isset($connexionSubmit)) {

        echo htmlspecialchars($_POST['username']);
    }
}



// FONCTION pour effacer les valeurs de session 
function UnsetPreviousSession()
{
    unset($_SESSION['username']);
}



// FONCTION pour effacer toute session
function deleteSession()
{
    $_SESSION = array();
    session_destroy();
}
