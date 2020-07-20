<?php 
session_start();

if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['id_user']))
{

    include("header.php"); 

?>

    <main>

        <section class="partenaire">
        <!-- Affichage des infos de l'acteur -->
        <?php
            $req = $bdd->prepare('SELECT * FROM partenaires WHERE id_acteur = ?');
            $req->execute(array($_GET['id_acteur']));
            /* ISSET? */
            $dataActeur = $req->fetch();
        
                echo $dataActeur['logo'];
                echo '<h2>' . $dataActeur['acteur'] . '</h2>';
                echo '<a href="' . $dataActeur['site'] . '">voir le site</a>';
                echo '<div class="text"><p>' .$dataActeur['description'] . '</p></div>';
      
        ?>

        </section>

        <section class="commentaires">
            <div class="commentaires-formulaires">

                <p> X commentaires </p>

                <div class="new_commentaire"> 

                        <label class ="open_popup" for ="popup_button"> Nouveau commentaire</label>

                        <input type ="checkbox" id ="popup_button">
  
                        <form class="new_commentaire_popup" method ="post" action="#">
                            <p>

                                <label class ="close_popup" for ="popup_button"> </label>

                                <label for="post">Ajoutez un nouveau commentaire sur 
                                    <strong>
                                        <?php echo $dataActeur['acteur']; ?>
                                    </strong> :</label>

                                <textarea id="post" name="post" rows="6" cols="50"></textarea>

                                <input type="submit" value="Envoyer" name="postPosted" />
                                                            
                            </p>
                            
                        </form> 
                </div>     

                <form class="commentaires-likes" method="post" action="#">
                    <p>
                        <input type="radio" name="avis" id="like" value="like">
                        <label class="likes" for="like"> x </label>
                        
                        <input type="radio" name="avis" id="dislike" value="dislike">
                        <label class="dislikes" for="dislike"> x </label>
                    </p>
                </form>
            </div>

            <ul class="commentaires-liste">
                

                    <?php
                    //cherche les commentaires sur le partenaire
                    $req2 = $bdd->prepare ('SELECT  p.post as comment,
                                                    DATE_FORMAT(p.date_add, "%d/%m/%Y") as commentDate,
                                                    a.prenom as autorName
                                            FROM post p
                                            INNER JOIN account a
                                            ON p.id_user = a.id_user
                                            WHERE p.id_acteur = ?
                                            
                                            ');

                    $req2->execute (array($_GET['id_acteur']));

                    while($dataComment = $req2->fetch()){

                            echo '<li><p>' . $dataComment['autorName']  . '</p>';
                            echo '<p>' . $dataComment['commentDate']  . '</p>';
                            echo '<p>' . htmlspecialchars($dataComment['comment'])  . '</p></li>';
                    }
                    
                    $req2->closeCursor();
                    
                ?>
                
            </ul>
        </section>

        <aside class="retour-accueil">
            <a href="accueil.php">retour à la page d'accueil</a>
        </aside>

    </main>

<?php 

    include("footer.php"); 

}
else
{
  header('Location: index.php');
}



// Ajouter un nouveau commentaire

if (isset($_POST['postPosted']) and !empty($_POST['post']))
{

    $req3 = $bdd->prepare   ('INSERT into post (id_user, id_acteur, date_add, post)
                            VALUES (:id_user, :id_acteur, NOW(), :post)
                            ');
    $req3->execute   (array(
                            'id_user' => $_SESSION['id_user'],
                            'id_acteur' => $dataActeur['id_acteur'],
                            'post' => htmlspecialchars($_POST['post'])
                            ));
    $req3->closeCursor();

}
?>