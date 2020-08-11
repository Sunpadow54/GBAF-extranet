<?php

session_start();

require_once('helper.php');


// FONCTION pour effacer les valeurs de session 
function unsetPreviousSession()
{
    unset($_SESSION['username']);
}



// FONCTION pour effacer toute session
function deleteSession()
{
    $_SESSION = array();
    session_destroy();
}



// Fonction cherche l'utilisateur
function searchUser($bdd, $userName)
{

    $req_select_info_user = $bdd->prepare('SELECT * FROM account WHERE username = ?');
    $req_select_info_user->bindValue(1, $userName);
    $req_select_info_user->execute();
    $dataAccount = $req_select_info_user->fetch();
    $req_select_info_user->closeCursor();

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
define("USERNAME_UNKNOWN",      "1");
define("EMPTY_FIELD",           "2");
define("PASSWORD_WRONG",        "3");
define("PASSWORD_INVALID",      "4");
define("ACCOUNT_UPDATE",        "5");
define("QUESTION",              "6");
define("ANSWER_WRONG",          "7");
define("PASSWORD_CAN_CHANGE",   "8");
define("PASSWORD_UPDATE",       "9");
define("USERNAME_EXIST",        "10");
define("WELCOME",               "11");

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

        case 5:
            echo "Vos changements ont bien été pris en compte";
            break;

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

        case 10:
            echo "Cet identifiant existe déjà ";
            break;

        case 11:
            echo "Bienvenue ! Vous pouvez vous connecter";
            break;
    }
}



// REGEX mp
$mpValid = "#(?=.*\d)(?=.*[A-Z])(?=.*[a-z])[0-9A-Za-z.-_]{4,}#";



// Fonction garde en mémoire les infos postées
function defaultInputValue($valuePost_Session, $defaultDataUser)
{

    if (isset($_POST['dataSubmit'])){

        echo htmlspecialchars($_POST[$valuePost_Session]);

    } elseif (isset($_SESSION[$valuePost_Session])) {

        echo htmlspecialchars($_SESSION[$valuePost_Session]);

    } elseif (isset($defaultDataUser)){
        
        echo htmlspecialchars($defaultDataUser);

    }
}