<?php include("header.php"); ?>

<main class="main-partenaire">
  <section class="partenaire">
    <img src="../images/CDE.png" alt="CDE">
    <h2>CDE (Chambre Des Entrepreneurs)</h2>
    <a href="https://www.CDE.chose">voir le site internet de CDE</a>
    <p>La CDE (Chambre Des Entrepreneurs) accompagne les entreprises dans leurs démarches de formation. 
Son président est élu pour 3 ans par ses pairs, chefs d’entreprises et présidents des CDE.</p>
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

<?php include("footer.php"); ?>