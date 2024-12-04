<?php
session_start();
include_once('fonctions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article = $_POST['article'];
    supprimer_du_panier($article);
    header('Location: panier.php');
    exit();
}
?>
