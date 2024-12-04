<?php
// session_start();
// if (!isset($_SESSION['profil'])) {
//     header('Location: ../index.php');
//     exit();
// }

// if (!isset($_SESSION['profil']) || $_SESSION['profil'] !== 'admin') {
    
//     header('Location: ../index.php');
//     exit();
// }
$titre = isset($article) ? "Modifier un article" : "Ajouter un article";
include_once('../inclus/header.php');
include_once('ajout_article.php');
include_once('modification_article.php');

$id = isset($article['idarticles']) ? $article['idarticles'] : '';
$nom = isset($article['nom']) ? $article['nom'] : '';
$ref = isset($article['ref']) ? $article['ref'] : '';
$prix = isset($article['prix']) ? $article['prix'] : '';
$tva = isset($article['tva']) ? $article['tva'] : '';
$promo = isset($article['promo']) ? $article['promo'] : '';
$new = isset($article['new']) ? $article['new'] : '';

$type = isset($type) ? $type : 'ajouter';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom :</label>
                    <input type="text" class="form-control" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="ref" class="form-label">Référence :</label>
                    <input type="text" class="form-control" name="ref" value="<?php echo htmlspecialchars($ref); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="prix" class="form-label">Prix :</label>
                    <input type="text" class="form-control" name="prix" value="<?php echo htmlspecialchars($prix); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tva" class="form-label">TVA :</label>
                    <input type="text" class="form-control" name="tva" value="<?php echo htmlspecialchars($tva); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="promo" class="form-label">Promotion :</label>
                    <input type="text" class="form-control" name="promo" value="<?php echo htmlspecialchars($promo); ?>">
                </div>
                <div class="mb-3">
                    <label for="new" class="form-label">Nouveauté :</label>
                    <select class="form-select" name="new" required>
                        <option value="1" <?php echo ($new == 1) ? 'selected' : ''; ?>>Oui</option>
                        <option value="0" <?php echo ($new == 0) ? 'selected' : ''; ?>>Non</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <?php echo $type === 'modifier' ? 'Modifier' : 'Ajouter'; ?>
                </button>
                <a href="liste_articles.php" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>
</div>

<?php
include_once('../inclus/footer.php');
?>
