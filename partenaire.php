<?php 
session_start();

if (isset($_SESSION['nom']) && isset($_SESSION['prenom']))
{

    include("header.php"); 

?>

    <main class="main-partenaire">

        <section class="partenaire">
        <!-- Affichage des infos de l'acteur -->
        <?php
            $req = $bdd->prepare('SELECT * FROM partenaires WHERE id_acteur = ?');
            $req->execute(array($_GET['id_acteur']));
            /* ISSET? */
            while ($donnees = $req->fetch())
            {
                echo $donnees['logo'];
                echo '<h2>' . $donnees['acteur'] . '</h2>';
                echo '<a href="' . $donnees['site'] . '">voir le site</a>';
                echo '<p>' .$donnees['description'] . '</p>';
            }
            $req->closeCursor();
        ?>
        </section>

        <section class="commentaires">
            <div class="commentaires-formulaires">
                <p> X commentaires </p>
                <form class="commentaires-new" method="post" action="#">
                    <p>
                        <label for="new-comment">Nouveau commentaire</label>
                        <div>
                            <textarea name="new-comment" rows="" cols=""></textarea>
                            <input type="submit" value="Envoyer" />
                        </div>
                    </p>
                </form>

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
            <a href="index.php">retour à la page d'accueil</a>
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