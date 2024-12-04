<?php
$titre = "Liste des Commandes";
include_once('../inclus/header.php');
include_once('../panier/fonctions.php');

try {
    $connexion = getDbConnection(); // Obtenez la connexion PDO

    // Récupérer l'identifiant de l'utilisateur connecté
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $profil = isset($_SESSION['profil']) ? $_SESSION['profil'] : null;

    // Récupérer les commandes du client connecté
    $commandes_client = [];
    if ($user_id) {
        $stmt = $connexion->prepare("
            SELECT c.*, u.username 
            FROM commande c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.user_id = :user_id 
            ORDER BY c.date_commande DESC
        ");
        $stmt->execute([':user_id' => $user_id]);
        $commandes_client = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les commandes de tous les clients uniquement pour Admin et Gest
    $toutes_commandes = [];
    if ($profil === 'admin' || $profil === 'gest') {
        $stmt = $connexion->query("
            SELECT c.*, u.username 
            FROM commande c 
            JOIN users u ON c.user_id = u.id 
            ORDER BY c.date_commande DESC
        ");
        $toutes_commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Erreur de récupération des commandes : " . $e->getMessage();
}
?>

<body>
    <?php if ($profil == 'client'): ?>
        <h2>Commandes du Client</h2>
        <?php if ($user_id): ?>
            <?php if (!empty($commandes_client)): ?>
                <table border="1">
                    <tr>
                        <th>ID Commande</th>
    <?php if ($profil === 'admin' || $profil === 'gest'): ?>
                        <th>Nom de l'acheteur</th>
    <?php endif; ?>
                        <th>Date de Commande</th>
                    </tr>
                    <?php foreach ($commandes_client as $commande): ?>
                        <tr>
                            <td><a href="details_commande.php?commande_id=<?= htmlspecialchars($commande['id']) ?>"><?= htmlspecialchars($commande['id']) ?></a></td>
                            <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucune commande passée.</p>
                <a href="../articles/liste_articles.php">Continuer vos achats</a>

            <?php endif; ?>
        <?php else: ?>
            <p>Vous devez être connecté pour voir vos commandes.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($profil === 'admin' || $profil === 'gest'): ?>
        <h2>Toutes les Commandes</h2>
        <?php if (!empty($toutes_commandes)): ?>
            <table border="1">
                <tr>
                    <th>ID Commande</th>
                    <th>Nom de l'Utilisateur</th>
                    <th>Date de Commande</th>
                </tr>
                <?php foreach ($toutes_commandes as $commande): ?>
                    <tr>
                        <td><a href="details_commande.php?commande_id=<?= htmlspecialchars($commande['id']) ?>"><?= htmlspecialchars($commande['id']) ?></a></td>
                        <td><?= htmlspecialchars($commande['username']) ?></td>
                        <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucune commande trouvée.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
