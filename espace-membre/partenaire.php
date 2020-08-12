<?php

session_start();

// REDIRECTION: NON CONNECTÉ
if (!isset($_SESSION['nom']) && !isset($_SESSION['prenom']) && !isset($_SESSION['id_user'])) {

    header('Location: index.php');
    exit();
}

// BDD
require_once('../core/helper.php');

$idActeur = htmlspecialchars($_GET['id_acteur']);



// A. Cherche les infos de l'Acteur
$req_data_acteur = $bdd->prepare('SELECT * FROM acteur WHERE id_acteur = ?');
$req_data_acteur->execute(array($idActeur));
$dataActeur = $req_data_acteur->fetch();
$req_data_acteur->closeCursor();



// B. Ajoute un nouveau commentaire unique

// Cherche si l'utilisateur a déjà commenté
$req_already_comment = $bdd->prepare('SELECT * FROM post WHERE id_user = ? AND id_acteur = ?');
$req_already_comment->execute(array($_SESSION['id_user'], $idActeur));
$userHasComment = $req_already_comment->fetch();
$req_already_comment->closeCursor();

// si il n'a pas déjà commenté
if (!$userHasComment) {

    $formComment = '';

    if (isset($_POST['newCommentPosted']) and !empty($_POST['post'])) {

        $req_insert_comment = $bdd->prepare('INSERT into post (id_user, id_acteur, date_add, post)
                                VALUES (:id_user, :id_acteur, NOW(), :post)
                                ');
        $req_insert_comment->execute(array(
            'id_user' => $_SESSION['id_user'],
            'id_acteur' => $dataActeur['id_acteur'],
            'post' => ($_POST['post'])
        ));
        $req_insert_comment->closeCursor();
    }
}

if ($userHasComment) {

    $formComment = 'Vous avez déjà commenté';
}



// C. Compte le nombre de commentaire sur l'acteur
$req_nbr_comments = $bdd->prepare('SELECT COUNT(*) as nbrComments FROM post WHERE id_acteur = ?');
$req_nbr_comments->execute(array($idActeur));
$commentsPosted = $req_nbr_comments->fetch();
$nbrcommentsPosted = $commentsPosted['nbrComments'];
$req_nbr_comments->closeCursor();



// D. Fonction Compte le nombre de 'like' et 'Dislike' sur l'acteur
function nbrLikeDislike($idActeur, $voteValue, $bdd)
{

    $req_nbr_like_dislike = $bdd->prepare('SELECT COUNT(vote) as `nombre` FROM `vote` WHERE id_acteur = ? AND vote = ?');

    $req_nbr_like_dislike->bindValue(1, $idActeur, PDO::PARAM_INT);
    $req_nbr_like_dislike->bindValue(2, $voteValue, PDO::PARAM_STR);

    $req_nbr_like_dislike->execute();
    $likeOrDislike = $req_nbr_like_dislike->fetch();
    $req_nbr_like_dislike->closeCursor();

    if (isset($likeOrDislike['nombre'])) {
        echo $likeOrDislike['nombre'];
    }
}



// E. Cherche si l'user a like ou a dislike
$req_vote_user = $bdd->prepare('SELECT vote FROM vote WHERE id_acteur = ? AND id_user = ?');
$req_vote_user->execute(array($_GET['id_acteur'], $_SESSION['id_user']));
$userVote = $req_vote_user->fetch();
$req_vote_user->closeCursor();

if (isset($userVote['vote'])) {
    if ($userVote['vote'] == 'like') {
        $iconeVoteLike = 'icone-like-active';
        $iconeVoteDislike = 'icone-dislike';
    } elseif ($userVote['vote'] == 'dislike') {
        $iconeVoteLike = 'icone-like';
        $iconeVoteDislike = 'icone-dislike-active';
    }
}
if (!isset($userVote['vote'])) {
    $iconeVoteLike = 'icone-like';
    $iconeVoteDislike = 'icone-dislike';
}



// F. Fonction qui affiche tous les commentaires sur l'acteur
function listCommentaires($bdd, $idActeur)
{
    $req_comment = $bdd->prepare('SELECT  p.post as comment, 
                                    DATE_FORMAT(p.date_add, "%d/%m/%Y") as commentDate,
                                    DATE_FORMAT(p.date_add, "%d/%m/%Y %T") as commentDateOrder, 
                                    a.prenom as autorName
                            FROM post p
                            INNER JOIN account a
                            ON p.id_user = a.id_user
                            WHERE p.id_acteur = ?
                            ORDER by commentDateOrder DESC');

    $req_comment->bindValue(1, $idActeur, PDO::PARAM_INT);
    $req_comment->execute();

    while ($dataComment = $req_comment->fetch()) {

        echo '<li>';
        echo '<p>' . htmlspecialchars($dataComment['autorName'])  . '</p>';
        echo '<p>' . $dataComment['commentDate']  . '</p>';
        echo '<p>' . htmlspecialchars($dataComment['comment'])  . '</p>';
        echo '</li>';
    }

    $req_comment->closeCursor();
}


// CONNECTÉ:
if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['id_user'])) {

    require_once('../layout/header.php');
    /* 	------------------------------------------------ HTML page Partenaire ------------------------------------------------ */
    ?>

    <main>
        <!-- A. Section infos de l'acteur -->
        <section class="partenaire">
            <?php
            echo $dataActeur['logo'];
            echo '<h2>' . $dataActeur['acteur'] . '</h2>';
            echo '<a href="' . $dataActeur['site'] . '">voir le site</a>';
            echo '<div class="text"><p>' . $dataActeur['description'] . '</p></div>'
            ?>
        </section>

        <!-- Section commentaires -->
        <section class="commentaires">
            <div class="commentaires_dynamic">
                <!-- C. Nombre de commentaires -->
                <h4> 
                    <?php echo $nbrcommentsPosted; ?> 
                    commentaires 
                </h4>

                <!-- Ajouter un nouveau commentaire -->
                <div class="new_commentaire">
                    <label class="open_popup" for="popup_button"> 
                        Nouveau commentaire
                    </label>
                    <input type="checkbox" id="popup_button"/>
                    <!-- B. fenêtre pop-up du formulaire -->
                    <form 
                        class="new_commentaire_formulaire"
                        method="post"
                        action="#"
                    >
                        <p>
                            <label class="close_popup" for="popup_button"></label>
                            <label for="post">
                                Ajoutez un nouveau commentaire sur 
                                <em> <?php echo $dataActeur['acteur']; ?> </em>
                                : 
                            </label>
                            <textarea id="post" name="post"><?php echo $formComment; ?></textarea>
                            <input 
                                type="submit" 
                                value="Envoyer" 
                                name="newCommentPosted" 
                            />
                        </p>
                    </form>
                </div>

                <!-- Likes / Dislikes -->
                <div class="commentaires_vote">
                    <!-- Ajoute un like (vote) -->
                    <a class="vote_like"
                        href="<?php echo 'vote.php?id_acteur=' . $dataActeur['id_acteur'] . '&vote=like'; ?>"
                    >
                        <!-- D. Nombre de like -->
                        <p>
                            <?php nbrLikeDislike($dataActeur['id_acteur'], 'like', $bdd); ?>
                        </p>

                        <!-- E. icone like -->
                        <img 
                            src="<?php echo '/images/' . $iconeVoteLike . '.png'; ?>" 
                            alt="like"
                        />
                    </a>

                    <!-- Ajoute un dislike (vote) -->
                    <a class="vote_dislike"
                        href="<?php echo 'vote.php?id_acteur=' . $dataActeur['id_acteur'] . '&vote=dislike'; ?>"
                    >
                        <!-- E. icone dislike -->
                        <img 
                            src="<?php echo '/images/' . $iconeVoteDislike . '.png'; ?>" 
                            alt="dislike"
                        />

                        <!-- D. Nombre de dislike -->
                        <p> 
                            <?php nbrLikeDislike($dataActeur['id_acteur'], 'dislike', $bdd); ?> 
                        </p>
                    </a>
                </div>
            </div>

            <!-- F. Liste de tous les commentaires -->
            <ul class="commentaires-list">
                <!--<li> -->
                <?php listCommentaires($bdd, $idActeur); ?>
            </ul>
        </section>

        <aside class="retour-accueil">
            <a href="accueil.php">retour à la page d'accueil</a>
        </aside>
    </main>

    <?php

    require_once('../layout/footer.php');
}
?>