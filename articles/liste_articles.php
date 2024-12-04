<?php
$titre = "Outillage";
include_once('../inclus/header.php');
try {
    // Connexion à la base de données
    $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les articles depuis la base de données
    $query = "SELECT * FROM articles";
    $result = $connexion->query($query);
    $articles = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur de récupération des articles : " . $e->getMessage();
}
?>
    <h2>Liste des Articles</h2>
    <?php if(isset($_SESSION['profil']) && ($_SESSION['profil'] === 'admin') |  ($_SESSION['profil'] === 'gest')): ?>
        <a href="ajout_article.php">Ajouter un article</a><br><br>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Référence</th>
            <th>Prix HT</th>
            <th>TVA</th>
            <th>Prix TTC</th>
            <th>Prix après promo</th>
            <th>Promotion</th>
            <th>Nouveauté</th>
            <?php if(isset($_SESSION['profil']) && ($_SESSION['profil'] === 'admin') | ($_SESSION['profil'] === 'gest')): ?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
        <?php foreach ($articles as $article) : 
            // prix HT
            $prix_ht = $article['prix'] / (1 + $article['tva'] / 100);
            // prix TTC
            $prix_ttc = $article['prix'];
            // prix après promo
            if ($article['promo'] !== NULL) {
                $prix_ttc -= $article['prix'] * $article['promo'] / 100;
            }
        ?>
            <tr>
                <td><?= htmlspecialchars($article['nom']) ?></td>
                <td><?= htmlspecialchars($article['ref']) ?></td>
                <td><?= number_format($prix_ht, 2) ?> €</td>
                <td><?= number_format($article['tva'], 2) ?> %</td>
                <td><?= number_format($article['prix'], 2) ?> €</td>
                <td><?= number_format($prix_ttc, 2) ?> €</td> <!-- Prix après promo -->
                <td><?= $article['promo'] !== NULL ? htmlspecialchars($article['promo']) . '%' : '' ?></td>
                <td><?= $article['new'] ? 'Oui' : '' ?></td>
                <?php if(isset($_SESSION['profil']) && ($_SESSION['profil'] === 'admin') |  ($_SESSION['profil'] === 'gest')): ?>

                <td>
                    <a href="form.php?id=<?= $article['idarticles'] ?>">Modifier</a> | 
                    <a href="suppression_article.php?id=<?= $article['idarticles'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer</a>
                </td>
                <?php else : ?>
                <td>
                    <form action="../panier/ajout_panier.php" method="post">
                        <input type="hidden" name="article" value="<?= htmlspecialchars($article['idarticles']) ?>">
                        <input type="number" name="quantite" value="1" min="1" max="99">
                        <button type="submit">Ajouter au panier</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
<?php include_once('../inclus/footer.php'); ?>
