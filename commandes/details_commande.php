<?php
$titre = "Détails de la Commande ".$_GET['commande_id'] ;
include_once('../inclus/header.php');
include_once('../panier/fonctions.php');

if (!isset($_GET['commande_id'])) {
    echo "ID de commande manquant.";
    exit;
}

$commande_id = $_GET['commande_id'];

try {
    $connexion = getDbConnection();

    // détails de la commande
    $stmt = $connexion->prepare("SELECT * FROM commande WHERE id = :commande_id");
    $stmt->execute([':commande_id' => $commande_id]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    // vérifie si la commande existe
    if (!$commande) {
        echo "Commande non trouvée.";
        exit;
    }

    $articles_commande = explode(',', $commande['articles']);
    $articles_details = [];
    $total_prix = 0;

    foreach ($articles_commande as $article_commande) {
        list($article_id, $quantite) = explode(':', $article_commande);
        $stmt = $connexion->prepare("SELECT * FROM articles WHERE idarticles = :id");
        $stmt->execute([':id' => $article_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($article) {
            $article['quantite'] = $quantite;
            $articles_details[] = $article;
            $total_prix += $article['prix'] * $quantite;
        }
    }
} catch (PDOException $e) {
    echo "Erreur de récupération des détails de la commande : " . $e->getMessage();
}
?>

<body>
    <h2>Détails de la Commande <?= htmlspecialchars($commande_id) ?></h2>
    <?php if (!empty($articles_details)): ?>
        <table border="1">
            <tr>
                <th>Nom</th>
                <th>Référence</th>
                <th>Quantité</th>
                <th>Prix</th>
            </tr>
            <?php foreach ($articles_details as $article): ?>
                <tr>
                    <td><?= htmlspecialchars($article['nom']) ?></td>
                    <td><?= htmlspecialchars($article['ref']) ?></td>
                    <td><?= htmlspecialchars($article['quantite']) ?></td>
                    <td><?= htmlspecialchars(($article['prix'] * $article['quantite'])) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <p><strong>Total :</strong> <?= htmlspecialchars($total_prix) ?> €</p>
    <?php else: ?>
        <p>Aucun article trouvé pour cette commande.</p>
    <?php endif; ?>
</body>
</html>
