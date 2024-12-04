<?php
include_once('../inclus/header.php');

if (isset($_GET['id'])) {
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "DELETE FROM articles WHERE idarticles = :id";
        $stmt = $connexion->prepare($query);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();

        header("Location: liste_articles.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur de suppression de l'article : " . $e->getMessage();
    }
} else {
    echo "ID de l'article non spécifié.";
    exit;
}
?>