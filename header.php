<?php

// Base de donnée connexion : 
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
    die('Erreur : ' . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />

		<title>GBAF extranet</title>

		<link rel="stylesheet" href="style.css" />
		<link
			rel="stylesheet"
			media="all and (min-device-width : 320px) and (max-device-width : 480px)"
			href="style-mobile.css"
		/>
		<link
			rel="stylesheet"
			media="all and (max-device-width: 1280px ) and (min-device-width: 481px)"
			href="style-tablette.css"
		/>

		<link
			href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;900&display=swap"
			rel="stylesheet"
		/>
	</head>

	<body>
		<header>
			<div class="header-contain">
				<!-- Logo GBAF -->
				<a class="header-logo" href="accueil.php">
					<img src="../images/GBAF.png" alt="Logo GBAF" />
				</a>

                <?php // affiche le profil uniquement si un utilsateur est connecté
                if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['id_user'])) {
                ?>

                <!-- Profil -->
                <ul class="header-profil">
                    <!-- Nom Prenom -->
                    <li class="profil_nom">
                        <p> <?php echo htmlspecialchars($_SESSION['nom']); ?> </p>
                        <p> <?php echo htmlspecialchars($_SESSION['prenom']); ?> </p>
                    </li>

                    <!-- bouton Déconnexion -->
                    <li>
                        <a href="deconnexion.php">Se déconnecter</a>
                    </li>

                    <!-- bouton Modifier son Profil -->
                    <li>
                        <a href="parametres-compte.php">Paramètres du compte</a>
                    </li>
                </ul>
                <?php
                }
                ?>
        </div>
    </header>