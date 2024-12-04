<?php
session_start();
include_once('fonctions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vider_panier();
    header('Location: panier.php');
    exit();
}
?>
