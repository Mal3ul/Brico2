<?php
include_once('../inclus/header.php');
include_once('fonctions.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    try {
        $connexion = getDbConnection(); // Obtenez la connexion PDO

        // Récupérer les articles du panier de l'utilisateur
        $stmt = $connexion->prepare("SELECT article_ref, quantite FROM panier WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $panier = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($panier) {
            // Préparer les données des articles pour les stocker
            $articles = [];
            foreach ($panier as $item) {
                $articles[] = $item['article_ref'] . ':' . $item['quantite'];
            }
            $articles_str = implode(',', $articles);

            // Créer une nouvelle commande
            $stmt = $connexion->prepare("INSERT INTO commande (user_id, articles) VALUES (:user_id, :articles)");
            $stmt->execute([':user_id' => $user_id, ':articles' => $articles_str]);

            // Vider le panier
            vider_panier_bdd($user_id);

            echo "Commande passée avec succès.";
        } else {
            echo "Panier introuvable.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la passation de commande : " . $e->getMessage();
    }
} else {
    echo "Vous devez être connecté pour passer une commande.";
}
?>
