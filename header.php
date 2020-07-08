<?php
// Base de donnée connexion : 
    try
    {
        $bdd = new PDO(
            'mysql:host=localhost;
            dbname=gbaf-extranet;
            charset=utf8',
            'root',
            '', 
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title> GBAF extranet</title>

        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" media="all and (min-device-width : 320px) and (max-device-width : 480px)" href="style-mobile.css">
        <link rel="stylesheet" media="all and (max-device-width: 1280px ) and (min-device-width: 481px)" href="style-tablette.css">

        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;900&display=swap" rel="stylesheet">  
    </head>
    
  <body>
    <header>
        <div class="header-contain">
            <a class="header-logo" href="accueil.php">
                <img src="../images/GBAF.png" alt="Logo GBAF">
            </a>

            <div class="header-profil">
<!--                 <input type="checkbox" id="profil-checkbox" > -->
                <div class="header-profil_nom"> 
                    <?php 
                        echo '<p>' . htmlspecialchars($_SESSION['nom']) . '</p>'; 
                        echo '<p>' . htmlspecialchars($_SESSION['prenom']) . '</p>'; 
                    ?> 
                </div>
                <a href="deconnexion.php">Se déconnecter</a>
                <a class="header-profil_overlay" href="#">Modifier mon profil</a>
            </div>
        </div>
    </header>