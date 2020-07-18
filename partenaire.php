<?php 
session_start();

if (isset($_SESSION['nom']) && isset($_SESSION['prenom']))
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

                                <label for="new_commentaire_add">Ajoutez un nouveau commentaire sur <strong>ACTEUR?</strong> :</label>

                                <textarea id="new_commentaire_add" name="new_commentaire_add" rows="6" cols="50"></textarea>

                                <input type="submit" value="Envoyer" />
                                                            
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
                <li>
                    <p>Prénom</p>
                    <p>Nom</p>
                    <p>text commentaire</p>
                </li>
                <li>
                    <p>Prénom</p>
                    <p>Nom</p>
                    <p>text commentaire</p>
                </li>
                <li>
                    <p>Prénom</p>
                    <p>Nom</p>
                    <p>text commentaire</p>
                </li>
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

?>