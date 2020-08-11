<?php

session_start();

// REDIRECTION: NON CONNECTÉ ou PAS DE VOTE
if (!isset($_SESSION['nom']) && !isset($_SESSION['prenom']) && !isset($_SESSION['id_user'])
    && !isset($_GET['vote']) && !isset($_GET['id_acteur'])) {

    header('Location: ../index.php');
    exit();
}



// CONNECTÉ
if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['id_user'])
    && isset($_GET['vote']) && isset($_GET['id_acteur'])) {

    require_once('../core/helper.php');


    //Cherche le vote de l'utilisateur sur la page acteur
    $req_vote_user = $bdd->prepare('SELECT vote FROM vote WHERE id_acteur = ? AND id_user = ?');
    $req_vote_user->execute(array($_GET['id_acteur'], $_SESSION['id_user']));
    $userVote = $req_vote_user->fetch();
    $req_vote_user->closeCursor();

    // Si il a pas voté
    if (!$userVote) {

        // on Ajoute son vote
        $req_insert_vote = $bdd->prepare('INSERT INTO vote (id_user, id_acteur, vote)
                                VALUES (:id_user, :id_acteur, :vote)');
        $req_insert_vote->execute(array(
            'id_user' => ($_SESSION['id_user']),
            'id_acteur' => ($_GET['id_acteur']),
            'vote' => ($_GET['vote'])
        ));

        $req_insert_vote->closeCursor();

        header('Location: partenaire.php?id_acteur=' . $_GET['id_acteur']);
        exit();
    } 

    if ($userVote && $_GET['vote'] != $userVote['vote']) {

        // on change son vote
        $req_update_vote = $bdd->prepare('UPDATE vote SET vote = :vote WHERE id_user = :id_user AND id_acteur = :id_acteur');
        $req_update_vote->execute(array(
            'vote' => ($_GET['vote']),
            'id_user' => ($_SESSION['id_user']),
            'id_acteur' => ($_GET['id_acteur']),
        ));
        $req_update_vote->closeCursor();

        header('Location: partenaire.php?id_acteur=' . $_GET['id_acteur']);
        exit();
    } 

    // REDIRECTION: vote identique
    if ($userVote && $_GET['vote'] == $userVote['vote']) {

        header('Location: partenaire.php?id_acteur=' . $_GET['id_acteur']);
        exit();
    }
}

