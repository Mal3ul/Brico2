<?php
$titre = "Ajouter un article";
include_once('../inclus/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO articles (nom, ref, prix, tva, promo, new) VALUES (:nom, :ref, :prix, :tva, :promo, :new)";
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':nom', $_POST['nom']);
        $stmt->bindParam(':ref', $_POST['ref']);
        $stmt->bindParam(':prix', $_POST['prix']);
        $stmt->bindParam(':tva', $_POST['tva']);
        $stmt->bindParam(':promo', $_POST['promo']);
        $stmt->bindParam(':new', $_POST['new']);

        $stmt->execute();

        header("Location: liste_articles.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur d'ajout d'article : " . $e->getMessage();
    }
}

$type = 'ajouter';
$article = null;
include_once('form.php');
// include_once('../inclus/footer.php');
?>
