<?php
session_start();
include_once('fonctions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article = $_POST['article'];
    $quantite = intval($_POST['quantite']);
    modifier_quantite($article, $quantite);
    header('Location: panier.php');
    exit();
}
?>
