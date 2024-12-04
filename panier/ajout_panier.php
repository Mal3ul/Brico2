<?php
session_start();
include_once('fonctions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article = $_POST['article'];
    $quantite = intval($_POST['quantite']);
    ajouter_au_panier($article, $quantite);
    header('Location: ../articles/liste_articles.php');
    exit();
}
?>
