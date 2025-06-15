<?php 
session_start();
$currentPage = basename($_SERVER['PHP_SELF']);
include_once('../inclus/footer.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <title><?php echo $titre ?></title> <!-- changement du titre de la page selon $titre -->
  <link rel="stylesheet" href="../inclus/index.css">
  <style>
    .navbar-nav {
        display: flex;
        justify-content: end;
        width: 100%;
    }
    .navbar-nav .nav-item {
        margin-right: 20px;
    }
    @media (max-width: 991.98px) {
        .navbar-nav {
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .navbar-nav .nav-item {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
    .nav-link { 
        padding-right: .5rem !important; padding-left: .5rem !important; 
    }
  </style>
  </style>
</head>
<header class="w-100 text-bg-dark text-center align-items">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <h2><a class="navbar-brand py-2 float-md-start mb-0" href="../index.php">Brico'Brac</a></h2>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav ml-auto mr-6" style="flex-direction: row;">
            <li class="nav-item <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?> mr-4">
                <a class="nav-link" href="../inclus/index.php">Accueil</a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'liste_articles.php') ? 'active' : ''; ?> mr-4">
                <a class="nav-link" href="../articles/liste_articles.php">Outillage</a>
            </li>
            <li class="nav-item <?php echo ($currentPage == 'panier.php') ? 'active' : ''; ?> mr-4">
                <a class="nav-link" href="../panier/panier.php">Panier</a>
            </li>
            <?php if (isset($_SESSION['profil'])) : ?>
                <li class="nav-item <?php echo ($currentPage == 'liste_commande.php') ? 'active' : ''; ?> mr-4">
                    <a class="nav-link" href="../commandes/liste_commande.php">Commandes</a>
                </li>
            <?php endif; ?>
                <?php if (isset($_SESSION['profil']) && ($_SESSION['profil'] === 'admin' || $_SESSION['profil'] === 'gest')) : ?>
            <li class="nav-item <?php echo ($currentPage == 'gestion_utilisateurs.php') ? 'active' : ''; ?> mr-4">
                <a class="nav-link" href="../gest/gestion_utilisateurs.php">Utilisateurs</a>
            </li>
            <?php endif; ?>
                <?php if (isset($_SESSION['profil'])) : ?>
            <li class="nav-item mr-4">
                <a class="nav-link" href="../connexion/deconnexion.php">Déconnexion</a>
            </li>
                <?php else : ?>
            <li class="nav-item mr-4">
                <a class="nav-link" href="../connexion/connexion.php">Connexion</a>
            </li>
                <?php endif; ?>
            <li class="nav-item <?php echo ($currentPage == 'contact.php') ? 'active' : ''; ?> mr-4">
                <a class="nav-link" href="../contact/contact.php">Contact</a>
            </li>
        </ul>
    </div>
</nav>
</header>
<body class="text-center w-100">
    