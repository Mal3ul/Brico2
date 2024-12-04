<?php
$titre = "Panier";
include_once('fonctions.php');
include_once('../inclus/header.php');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$articles_panier = $user_id ? obtenir_panier_bdd($user_id) : obtenir_panier();
$cookie_present = isset($_COOKIE['panier']) && !empty($_COOKIE['panier']);
?>
<body class="text-center">
    <h2>Votre Panier</h2>
    <table border="1">
        <tr>
            <?php if (!empty($articles_panier)) : ?>
            <th>Référence</th>
            <th>Quantité</th>
            <th>Actions</th>
            <?php else: ?>
                <p class="text-center">Votre Panier est vide</p>
            <?php endif; ?>
            
        </tr>
        <?php foreach ($articles_panier as $article => $quantite) : ?>
            <tr>
                <td><?= htmlspecialchars($article) ?></td>
                <td>
                    <form action="modifier_panier.php" method="post" style="display:inline;">
                        <input type="hidden" name="article" value="<?= htmlspecialchars($article) ?>">
                        <input type="number" name="quantite" value="<?= htmlspecialchars($quantite) ?>" min="1">
                        <button type="submit">Modifier</button>
                    </form>
                </td>
                <td>
                    <form action="supprimer_article.php" method="post" style="display:inline;">
                        <input type="hidden" name="article" value="<?= htmlspecialchars($article) ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php if (!empty($articles_panier)) : ?>
    <form action="vider_panier.php" method="post">
        <br>
        <button type="submit">Vider le panier</button>
    </form>
    <?php endif; ?>
    <a href="../articles/liste_articles.php">Continuer vos achats</a>
    <p>ou</p>
    <?php if ($user_id): ?>
        <form action="../panier/commande.php" method="post">
            <button type="submit">Passer commande</button>
        </form>
    <?php else: ?>
        <p><a href="../connexion/connexion.php">Connectez-vous</a> <?php if (!empty($articles_panier)) : ?>pour passer commande. <?php endif ?></p>
    <?php endif; ?>
</body>
</html>
