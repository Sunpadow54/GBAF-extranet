<?php include("header.php"); ?>

<main>
  <section class="partenaire">
    <img src="../images/CDE.png" alt="CDE">
    <h2>CDE (Chambre Des Entrepreneurs)</h2>
    <a href="https://www.CDE.chose">site internet CDE</a>
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
                x
                <input type="radio" name="likes" value="like">
                <label for="like"> </label>

                <input type="radio" name="likes" id="dislike">
                <label for="dislike"> </label>
                x
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

  <aside>
      <a href="accueil.php">retour à la page d'accueil</a>
  </aside>

</main>

<?php include("footer.php"); ?>