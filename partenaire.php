<?php 
session_start();

if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['id_user'])) {

    include("header.php"); 

    $idActeur = htmlspecialchars($_GET['id_acteur']);

    // Cherche l'Acteur
    $req = $bdd->prepare('SELECT * FROM partenaires WHERE id_acteur = ?');
    $req->execute(array($idActeur));
    $dataActeur = $req->fetch();
    $req->closeCursor();


    // Compte le nombre de commentaire sur l'acteur
    $req2 = $bdd->prepare('SELECT COUNT(*) as nbrComments FROM post WHERE id_acteur = ?');
    $req2->execute(array($idActeur));
    $commentsPosted = $req2->fetch();
    $nbrcommentsPosted = $commentsPosted['nbrComments'];
    $req2->closeCursor();


    // Fonction qui affiche tous les commentaires sur l'acteur
    function listCommentaires ($bdd, $idActeur)
    {
        $req3 = $bdd->prepare ('SELECT  p.post as comment, DATE_FORMAT(p.date_add, "%d/%m/%Y") as commentDate, a.prenom as autorName
                                FROM post p
                                INNER JOIN account a
                                ON p.id_user = a.id_user
                                WHERE p.id_acteur = ?
                                ORDER by commentDate DESC');

        $req3->bindValue(1, $idActeur, PDO::PARAM_INT);
        $req3->execute ();

        while($dataComment = $req3->fetch()) {

            echo '<li>';
                echo '<p>' . htmlspecialchars($dataComment['autorName'])  . '</p>';
                echo '<p>' . $dataComment['commentDate']  . '</p>';
                echo '<p>' . htmlspecialchars($dataComment['comment'])  . '</p>';
            echo '</li>';

        }
        
        $req3->closeCursor();
    }  
                            
    
    // Ajoute un nouveau commentaire
    if (isset($_POST['newCommentPosted']) and !empty($_POST['post'])) {

        $req4 = $bdd->prepare ('INSERT into post (id_user, id_acteur, date_add, post)
                                VALUES (:id_user, :id_acteur, NOW(), :post)
                                ');
        $req4->execute   (array(
                                'id_user' => $_SESSION['id_user'],
                                'id_acteur' => $dataActeur['id_acteur'],
                                'post' => ($_POST['post'])
                                ));
        $req4->closeCursor();
    }


    // Fonction Compte le nombre de 'like' et 'Dislike' sur l'acteur
    function nbrLikeDislike ($idActeur, $voteValue, $bdd)
    {

        $req5 = $bdd->prepare ('SELECT COUNT(vote) as `nombre` FROM `vote` WHERE id_acteur = ? AND vote = ?');

        $req5->bindValue(1, $idActeur, PDO::PARAM_INT);
        $req5->bindValue(2, $voteValue, PDO::PARAM_STR);

        $req5->execute();
        $LikeOrDislike = $req5->fetch();
        $req5->closeCursor(); 
        
        if (isset($LikeOrDislike['nombre'])) {
            echo $LikeOrDislike['nombre'];
		} else {
            echo "0";
		}
		
    }
    

    // Cherche si l'user a like ou a dislike
    $req6 = $bdd->prepare ('SELECT vote FROM vote WHERE id_acteur = ? AND id_user = ?');
    $req6->execute (array($_GET['id_acteur'], $_SESSION['id_user']));
    $userVote = $req6->fetch();
    $req6->closeCursor();

    if(isset($userVote['vote'])) { 
        if ($userVote['vote'] == 'like') {
            $iconeVoteLike = 'icone-like-active';
            $iconeVoteDislike = 'icone-dislike';
        }
        elseif($userVote['vote'] == 'dislike') {            
            $iconeVoteLike = 'icone-like';
            $iconeVoteDislike = 'icone-dislike-active';            
        }
    } else {
        $iconeVoteLike = 'icone-like';
        $iconeVoteDislike = 'icone-dislike';
    } 

?>

    <main>
        <section class="partenaire">
            <!-- Affichage des infos de l'acteur -->
            <?php 
                echo $dataActeur['logo'];
                echo '<h2>' . $dataActeur['acteur'] . '</h2>';
                echo '<a href="' . $dataActeur['site'] . '">voir le site</a>';
                echo '<div class="text"><p>' .$dataActeur['description'] . '</p></div>'
            ?>

        </section>

        <section class="commentaires">
            <div class="commentaires-formulaires">
                
                <!-- Affichage du nombre de commentaire sur l'acteur-->
                <p> <?php echo $nbrcommentsPosted; ?> commentaires </p>

                <div class="new_commentaire"> 

                    <label class ="open_popup" for ="popup_button"> Nouveau commentaire</label>

                    <input type ="checkbox" id ="popup_button">

                    <form class="new_commentaire_popup" method ="post" action="#">
                        <p>

                            <label class ="close_popup" for ="popup_button"> </label>

                            <label for="post">Ajoutez un nouveau commentaire sur 
                                <strong>
                                    <?php echo $dataActeur['acteur']; ?>
                                </strong> :
                            </label>

                            <textarea id="post" name="post" rows="6" cols="50"></textarea>

                            <input type="submit" value="Envoyer" name="newCommentPosted" />
                                                        
                        </p> 
                    </form> 
                </div>     

                <div class="commentaires-likes">
                    
                    <p>
                        <!-- Affiche le nombre de like -->
                        <?php nbrLikeDislike($dataActeur['id_acteur'], 'like', $bdd); ?>

                    </p>

                    <a href= "<?php echo 'vote.php?id_acteur=' . $dataActeur['id_acteur'] . '&vote=like'; ?>" > 

                        <img src="<?php echo '../images/' . $iconeVoteLike . '.png'; ?>" alt="like">

                    </a>

                    <a href= "<?php echo 'vote.php?id_acteur=' . $dataActeur['id_acteur'] . '&vote=dislike'; ?>" > 
                        
                        <img src="<?php echo '../images/' . $iconeVoteDislike . '.png'; ?>" alt="dislike">

                    </a>

                    <p>
                        <!-- Affiche le nombre de dislike -->
                        <?php nbrLikeDislike($dataActeur['id_acteur'], 'dislike', $bdd); ?> 
                         
                    </p>

                </div>
            </div>

            <ul class="commentaires-liste">

                <!-- Affiche liste des commentaires <li> -->
                <?php listCommentaires($bdd, $idActeur); ?>
                
            </ul>
        </section>

        <aside class="retour-accueil">
            <a href="accueil.php">retour à la page d'accueil</a>
        </aside>

    </main>

<?php 

    include("footer.php"); 

} else {

  header('Location: index.php');

}

?>