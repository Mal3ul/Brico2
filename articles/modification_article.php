<?php
$titre = "Modifier un article";
include_once('../inclus/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "UPDATE articles SET nom = :nom, ref = :ref, prix = :prix, tva = :tva, promo = :promo, new = :new WHERE idarticles = :id";
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':nom', $_POST['nom']);
        $stmt->bindParam(':ref', $_POST['ref']);
        $stmt->bindParam(':prix', $_POST['prix']);
        $stmt->bindParam(':tva', $_POST['tva']);
        $stmt->bindParam(':promo', $_POST['promo']);
        $stmt->bindParam(':new', $_POST['new']);
        $stmt->bindParam(':id', $_POST['id']);

        $stmt->execute();

        header("Location: liste_articles.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur de modification d'article : " . $e->getMessage();
    }
} elseif (isset($_GET['id'])) {
    
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM articles WHERE idarticles = :id";
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();

        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article) {
            echo "Article non trouvé.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur de récupération de l'article : " . $e->getMessage();
        exit;
    }

    $type = 'modifier';
    include_once('form.php');
} 
// else {
//     echo "ID de l'article non spécifié.";
//     exit;
// }
?>
